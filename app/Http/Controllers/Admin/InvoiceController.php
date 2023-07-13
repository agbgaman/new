<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display invoice settings
     *
     * @return Response
     */
    public function index()
    {
        $invoice_rows = ['invoice_currency', 'invoice_language', 'invoice_vendor', 'invoice_vendor_website', 'invoice_address', 'invoice_city', 'invoice_state', 'invoice_postal_code', 'invoice_country', 'invoice_phone', 'invoice_vat_number'];
        $invoice = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $invoice_rows)) {
                $invoice[$row['name']] = $row['value'];
            }
        }

        return view('admin.finance.invoice.index', compact('invoice'));
    }


    /**
     * Store invoice details in database
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'invoice_vendor' => 'required',
        ]);

        $rows = ['invoice_currency', 'invoice_language', 'invoice_vendor', 'invoice_vendor_website', 'invoice_address', 'invoice_city', 'invoice_state', 'invoice_postal_code', 'invoice_country', 'invoice_phone', 'invoice_vat_number'];

        foreach ($rows as $row) {
            Setting::where('name', $row)->update(['value' => $request->input($row)]);
        }

        return redirect()->back()->with('success', __('Invoice settings successfully updated'));
    }

    public function invoices(Request $request)
    {

        if ($request->ajax()) {

            $data = Invoice::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
//                ->addColumn('actions', function($row){
//                    $actionBtn ='<div>
//                                     <a href="'. route("admin.csv.edit", $row["id"] ). '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
//                                     <a class="deleteUserButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
//                                 </div>';
//                    return $actionBtn;
//                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('userName', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::where('id', $row["user_id"])->first();
                        if ($user) {
                            $userName = '<span>' . User::find($row["user_id"])->name . '</span>';
                            return $userName;
                        } else {
                            return $row["user_id"];
                        }
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('project-name', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["project_name"] . '"">' . ucfirst($row["project_name"]) . '</span>';
                    return $text;
                })
                ->rawColumns(['created-on', 'userName', 'project-name', 'actions'])
                ->make(true);

        }

        return view('admin.finance.invoice.list');
    }

    public function create()
    {
        return view('admin.finance.invoice.create');
    }

    public function invoices_store(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required',
        ]);

        $file = $request->file('csv');

        $invoices = $this->csvToArray($file);

        DB::beginTransaction();
        try {
            foreach ($invoices as $invoice) {

                $user = User::where('email', $invoice['user_id'])->first();
                $referral_user = User::where('email', $invoice['referral'])->first();

                if ($user) {
                    $created_invoice = Invoice::create([
                        'user_id'           => $user->id,
                        'project_name'      => empty($invoice['project_name']) ? null : $invoice['project_name'],
                        'accepted_data'     => empty($invoice['accepted_data']) ? 0 : $invoice['accepted_data'],
                        'rejected_data'     => empty($invoice['rejected']) ? 0 : $invoice['rejected'],
                        'referral_email'    => empty($invoice['referral']) ? null : $invoice['referral'],
                        'earning'           => empty($invoice['earning']) ? 0 : $invoice['earning'],
                        'commission'        => empty($invoice['commission']) ? 0 : $invoice['commission'],
                    ]);

                    if ($referral_user) {
                        $referral = Referral::create([
                            'referrer_id'       => $referral_user->id,
                            'referrer_email'    => $referral_user->email,
                            'referred_id'       => $user->id,
                            'referred_email'    => $user->email,
                            'order_id'          => 0,
                            'payment'           => empty($invoice['earning']) ? 0 : $invoice['earning'],
                            'commission'        => empty($invoice['commission']) ? 0 : $invoice['commission'],
                            'rate'              => config('payment.referral.payment.commission'),
                            'status'            => 'Complete',
                            'gateway'           => null,
                            'purchase_date'     => now(),
                        ]);

                        $referral_user->balance += $referral->commission;
                        $referral_user->save();
                    }
                    $user->balance += $created_invoice->earning;
                    $user->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', __('Report has been saved Successfully'));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            // Read the first line and remove the BOM character if present
            $firstLine = fgets($handle);
            $cleanFirstLine = str_replace("\xEF\xBB\xBF", '', $firstLine);

            // Convert the cleaned first line to an array and remove extra spaces
            $header = array_map(function ($header) {
                return strtolower(trim($header)); // convert to lowercase
            }, str_getcsv($cleanFirstLine, $delimiter));

            // Read the remaining lines
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Remove extra spaces from the row values
                $row = array_map(function ($value) {
                    return trim($value);
                }, $row);

                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    public function invoicesUserList(Request $request)
    {

        if ($request->ajax()) {
            $data = Invoice::where('user_id', auth()->user()->id)->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('userName', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::where('id', $row["user_id"])->first();
                        if ($user) {
                            $userName = '<span>' . User::find($row["user_id"])->name . '</span>';
                            return $userName;
                        } else {
                            return $row["user_id"];
                        }
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('project-name', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["project_name"] . '"">' . ucfirst($row["project_name"]) . '</span>';
                    return $text;
                })
                ->addColumn('earning-currency', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["earning"] . '"">' . ucfirst($row["earning"]).' '.auth()->user()->currency . '</span>';
                    return $text;
                })
                ->rawColumns(['created-on', 'userName', 'project-name', 'actions','earning-currency'])
                ->make(true);

        }
        return view('admin.finance.invoice.userList');
    }
}
