<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Payment;
use DataTables;

class PurchaseHistoryController extends Controller
{
    /**
     * List all user payments
     */
    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = Payment::where('user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                        <a href="'. route("user.purchases.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons view-action-button" title="View Transaction"></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y H:i:s').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["plan_type"]).'">'.ucfirst($row["plan_type"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-characters', function($row){
                        $custom_group = '<span class="font-weight-bold">'.number_format($row["characters"]) . '</span>';
                        return $custom_group;
                    })
                    ->addColumn('custom-minutes', function($row){
                        $custom_group = '<span class="font-weight-bold">'.number_format($row["minutes"]) . '</span>';
                        return $custom_group;
                    })
                    ->addColumn('custom-order', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.$row["order_id"].'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span><br><span class="text-muted">'.$row["price"] . ' ' .$row['currency'].'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-30" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-20" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="w-40" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="w-40" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
                                break;
                            case 'BankTransfer':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="BankTransfer Gateway" class="w-40" src="' . URL::asset('img/payments/bank-transfer.png') . '"></div>';
                                break;
                            default:
                                $custom_gateway = '<div class="overflow-hidden">Unknown</div>';
                                break;
                        }
                        
                        return $custom_gateway;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-gateway', 'custom-amount', 'custom-plan-name', 'custom-order', 'custom-frequency', 'custom-characters', 'custom-minutes'])
                    ->make(true);
                    
        }

        return view('user.purchase.index');
    }


    /**
     * List user susbsriptions
     */
    public function subscriptions(Request $request)
    {        
        if ($request->ajax()) {
            $data = Subscriber::select('subscribers.*', 'subscription_plans.plan_name')->join('subscription_plans', 'subscription_plans.id', '=', 'subscribers.plan_id')->where('subscribers.user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn =  '<div>                                            
                                            <a class="cancelSubscriptionButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-file-slash table-action-buttons delete-action-button" title="Cancel Sunbscription"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-until', function($row){
                        $custom_until = '<span class="font-weight-bold">'.date_format($row["active_until"], 'd M Y').'</span>';
                        return $custom_until;
                    })
                    ->addColumn('custom-subscription-id', function($row){
                        $custom = '<span class="font-weight-bold">'.$row["subscription_id"].'</span>';
                        return $custom;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box subscription-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span><br><span class="text-muted">'.$row["characters"].'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-30" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-20" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="w-30" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="w-30" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
                                break;
                            case 'BankTransfer':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="BankTransfer Gateway" class="w-30" src="' . URL::asset('img/payments/bank-transfer.png') . '"></div>';
                                break;
                            default:
                                $custom_gateway = '<div class="overflow-hidden">Unknown</div>';
                                break;
                        }
                        
                        return $custom_gateway;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-gateway', 'custom-until', 'custom-plan-name', 'custom-subscription-id'])
                    ->make(true);
                    
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $id)
    {
        if ($id->user_id == Auth::user()->id){

            return view('user.purchase.show', compact('id'));     

        } else{
            return redirect()->route('user.purchases');
        }
    }

}
