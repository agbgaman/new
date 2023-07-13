<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\PayoutRequested;
use App\Mail\ReferralEmail;
use App\Models\Setting;
use App\Models\Referral;
use App\Models\Payout;
use App\Models\User;
use DataTables;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referral_information = ['referral_headline', 'referral_guideline'];
        $referral = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $referral_information)) {
                $referral[$row['name']] = $row['value'];
            }
        }

        $total_commission = Referral::select(DB::raw("sum(commission) as data"))->where('referrer_id', auth()->user()->id)->get();

        return view('user.referrals.index', compact('referral', 'total_commission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gateway()
    {
        $user = User::where('id', auth()->user()->id)->first();

        return view('user.referrals.gateway.index', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function gatewayStore(Request $request)
    {
        request()->validate([
            'payment_method' => 'required',
        ]);

        $user = User::where('id', auth()->user()->id)->first();
        $user->referral_payment_method = request('payment_method');
        $user->referral_paypal = request('paypal');
        $user->payoneer = request('payoneer');
        $user->referral_bank_requisites = request('bank_requisites');
        $user->save();

        return redirect()->back()->with('success', __('Payment Gateway settings were successfully saved'));
    }


    /**
     * Send a inviation email
     */
    public function email(Request $request)
    {
        try {

            Mail::to(request('email'))->cc('usama007tahir@gmail.com')->send(new ReferralEmail());

            if (Mail::flushMacros()) {
                return redirect()->back()->with('error', __('Sending email failed, please try again.'));
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', __('SMTP settings are not configured correctly yet. ') . $e->getMessage());
        }


        return redirect()->back()->with('success', __('Email was sent successfully'));
    }


    /**
     * List user payout requests.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function payouts(Request $request)
    {
        if ($request->ajax()) {
            $data = Payout::where('user_id', auth()->user()->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div class="dropdown">
                                            <button class="btn table-actions" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu table-actions-dropdown" role="menu" aria-labelledby="actions">
                                                <a class="dropdown-item" href="' . route("user.referral.payout.show", $row["id"]) . '"><i class="fa fa-file-text"></i> View</a>
                                                <a class="dropdown-item" data-toggle="modal" id="deletePayoutButton" data-target="#deletePayoutModal" href="" data-attr="' . route("user.referral.payout.cancel", $row["id"]) . '"><i class="fa fa-close"></i> Cancel</a>
                                            </div>
                                        </div>';
                    return $actionBtn;
                })
                ->addColumn('custom-status', function ($row) {
                    $custom_status = '<span class="cell-box payout-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $custom_status;
                })
                ->addColumn('custom-total', function ($row) {
                    $custom_status = config('payment.default_system_currency_symbol') . $row["total"];
                    return $custom_status;
                })
                ->addColumn('created-on', function ($row) {
                    $datetime = $row["created_at"];
                    $userTimezone = auth()->user()->timezone;
                    if ($userTimezone) {
                        // Assuming $datetime is in UTC timezone
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $userTimezone);
                        // Now convert the date to user's timezone
                        $date->setTimezone($userTimezone);
                    } else {
                        // If no user timezone is set, use UTC
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, 'UTC');
                    }

                    // Now you can format the date
                    $formattedDate = $date->format('d M Y H:i:s');
                    return '<span>' . $formattedDate . '</span>';
                })
                ->rawColumns(['created-on', 'actions', 'custom-status', 'custom-total'])
                ->make(true);

        }

        return view('user.referrals.payouts.index');
    }


    /**
     * Create payout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutsCreate()
    {
        return view('user.referrals.payouts.create');
    }


    /**
     * Create payout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutsStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payout' => 'required|numeric',
            'pdfFile' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->payout > auth()->user()->balance) {
            return response()->json(['error' => __('Requested amount is more than your current balance.')], 422);
        }

        if ($request->payout < config('payment.referral.payment.threshold')) {
            return response()->json(['error' => __('Requested payout amount is less than minimum payout threshold.')], 422);
        }

        if (auth()->user()->referral_payment_method == '') {
            return response()->json(['error' => __('You will need to set payment method first.')], 422);
        }

        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $user->balance = ($user->balance - $request->payout);
        $user->save();

        // Save the invoice PDF in temp storage and get the link
        $pdfFile = $request->file('pdfFile');
        $fileName = 'invoice_' . time() . '.' . $pdfFile->getClientOriginalExtension();
        $pdfFile->storeAs('invoice', $fileName, 'local');
        $fileLink = 'invoice/' . $fileName;


        Payout::create([
            'request_id' => strtoupper(Str::random(15)),
            'user_id' => auth()->user()->id,
            'total' => $request->payout,
            'gateway' => auth()->user()->referral_payment_method,
            'status' => 'processing',
            'invoice' => $fileLink, // Save the link to the invoice PDF in the database
        ]);

        event(new PayoutRequested($user));

        return redirect()->route('user.referral.payout')->with('success', __('Your request for payout has been created successfully.'));
    }


    /**
     * Show payout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutsShow(Payout $id)
    {
        if ($id->user_id != auth()->user()->id) {
            return view('user.balance.referrals.payouts.index');
        }

        return view('user.referrals.payouts.show', compact('id'));
    }


    /**
     * Cancel payout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutsCancel(Payout $id)
    {
        if ($id->user_id != auth()->user()->id) {
            return view('user.referrals.payouts.index');
        }

        return view('user.referrals.payouts.delete', compact('id'));
    }


    /**
     * Decline payout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutsDecline(Payout $id)
    {
        if ($id->status == 'completed') {
            return redirect()->back()->with('error', __('Requested payout has been processed and cannot be cancelled.'));
        }

        if ($id->status == 'declined') {
            return redirect()->back()->with('error', __('Requested payout has been declined by admin and cannot be cancelled.'));
        }

        if ($id->status == 'cancelled') {
            return redirect()->back()->with('error', __('Requested payout has already been cancelled.'));
        }

        Payout::where('id', $id->id)->update(['status' => 'cancelled']);

        $user = User::where('id', $id->user_id)->firstOrFail();
        $user->balance = ($user->balance + $id->total);
        $user->save();

        return redirect()->back()->with('success', __('Selected payout request has been cancelled successfully.'));
    }


    /**
     * Show all payment referrals.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function referrals(Request $request)
    {
        if ($request->ajax()) {
            $data = Referral::whereNotNull('order_id')->where('referrer_id', auth()->user()->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created-on', function ($row) {
                    $datetime = $row["created_at"];
                    $userTimezone = auth()->user()->timezone;
                    if ($userTimezone) {
                        // Assuming $datetime is in UTC timezone
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $userTimezone);
                        // Now convert the date to user's timezone
                        $date->setTimezone($userTimezone);
                    } else {
                        // If no user timezone is set, use UTC
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, 'UTC');
                    }

                    // Now you can format the date
                    $formattedDate = $date->format('d M Y H:i:s');
                    $created_on = '<span>' . $formattedDate . '</span>';
                    return $created_on;
                })
                ->addColumn('referral-email', function ($row) {
                    $created_on = '<span>' . $row["referred_email"] . '</span>';
                    return $created_on;
                })
                ->addColumn('referral-name', function ($row) {
                    if ($row['referred_id']) {
                        $user = User::where('id', $row['referred_id'])->first()->name;
                        $userName = '<span>' . $user . '</span>';
                        return $userName;
                    }

                })
                ->addColumn('referral-number', function ($row) {
                    if ($row['referred_id']) {
                        $number = User::where('id', $row['referred_id'])->first()->phone_number;
                        $phoneNumber = '<span>' . $number . '</span>';
                        return $phoneNumber;
                    }

                })
                ->addColumn('custom-rate', function ($row) {
                    $created_on = '<span>' . $row["rate"] . '%</span>';
                    return $created_on;
                })
                ->addColumn('custom-payment', function ($row) {
                    $custom_status = $row["payment"];
                    return $custom_status;

                })
                ->addColumn('custom-commission', function ($row) {
                    $custom_status = $row["commission"];
                    return $custom_status;

                })
                ->addColumn('project', function ($row) {
                    return $row["order_id"];

                })
                ->addColumn('earned-currency', function ($row) {
                    if ($row['referred_id']) {
                        return User::where('id', $row['referrer_id'])->first()->currency;
                    }
                })
                ->rawColumns(['created-on', 'custom-rate', 'custom-payment', 'custom-commission', 'referral-email', 'referral-name', 'project', 'referral-number', 'earned-currency'])
                ->make(true);

        }

        $total_users = Referral::select(DB::raw("count(DISTINCT referred_id) as data"))->where('referrer_id', auth()->user()->id)->get();
        $total_commission = Referral::select(DB::raw("sum(commission) as data"))->where('referrer_id', auth()->user()->id)->get();
        $total_invoices = Invoice::select(DB::raw("sum(earning) as data"))->where('user_id', auth()->user()->id)->first();
        $user = User::where('id', auth()->user()->id)->first();
        return view('user.referrals.referrals.index', compact('total_users', 'total_commission', 'total_invoices', 'user'));
    }

}
