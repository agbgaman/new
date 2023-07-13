<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Services\Statistics\PaymentsService;
use App\Services\Statistics\CostsService;
use App\Services\Statistics\VoiceoverService;
use App\Services\Statistics\TranscribeService;
use App\Models\Subscriber;
use App\Models\Payment;
use App\Models\User;
use DataTables;

class FinanceController extends Controller
{
    /**
     * Display finance dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $cost = new CostsService($year, $month);
        $payment = new PaymentsService($year, $month);
        $tts = new VoiceoverService($year, $month);
        $stt = new TranscribeService($year, $month);

        $total_data_monthly = [
            'income_current_month' => $payment->getTotalPaymentsCurrentMonth(),
            'income_past_month' => $payment->getTotalPaymentsPastMonth(),
            'spending_current_month' => $cost->getTotalCostForTextCurrentMonth() + $cost->getTotalCostForSecondsCurrentMonth(),
            'spending_past_month' => $cost->getTotalCostForTextPastMonth() + $cost->getTotalCostForSecondsPastMonth(),
            'purchased_chars' => $payment->getTotalPurchasedCharactersCurrentMonth(),
            'purchased_chars_past' => $payment->getTotalPurchasedCharactersPastMonth(),
            'paid_chars' => $tts->getTotalPaidCharsUsageMonthly(),
            'paid_chars_past' => $tts->getTotalPaidCharsUsagePastMonth(),
            'aws_current_month' => $cost->getTotalAWSCostCurrentMonth(),
            'azure_current_month' => $cost->getTotalAzureCostCurrentMonth(),
            'gcp_current_month' => $cost->getTotalGCPCostCurrentMonth(),
            'ibm_current_month' => $cost->getTotalIBMCostCurrentMonth(),
            'paid_minutes' => $stt->getTotalPaidMinutesUsageMonthly(),
            'paid_minutes_past' => $stt->getTotalPaidMinutesUsagePastMonth(),
            'purchased_minutes' => $payment->getTotalPurchasedMinutesCurrentMonth(),
            'purchased_minutes_past' => $payment->getTotalPurchasedMinutesPastMonth(),
        ];

        $total_data_yearly = [
            'total_income' => $payment->getTotalPaymentsCurrentYear(),
            'total_spending' => $cost->getTotalCostForTextCurrentYear(),
            'total_purchased_chars' => $payment->getTotalPurchasedCharactersCurrentYear(),
            'total_purchased_chars_used' =>$tts->getTotalPaidCharsUsageYearly(),
            'aws_current_year' => $cost->getTotalAWSCostCurrentYear(),
            'azure_current_year' => $cost->getTotalAzureCostCurrentYear(),
            'gcp_current_year' => $cost->getTotalGCPCostCurrentYear(),
            'ibm_current_year' => $cost->getTotalIBMCostCurrentYear(),
            'aws_current_year_std' => $tts->getAWSStandardUsageYearly(),
            'azure_current_year_std' => $tts->getAzureStandardUsageYearly(),
            'gcp_current_year_std' => $tts->getGCPStandardUsageYearly(),
            'aws_current_year_nrl' => $tts->getAWSNeuralUsageYearly(),
            'azure_current_year_nrl' => $tts->getAzureNeuralUsageYearly(),
            'gcp_current_year_nrl' => $tts->getGCPNeuralUsageYearly(),
            'ibm_current_year_nrl' => $tts->getIBMNeuralUsageYearly(),
            'total_purchased_minutes' => $payment->getTotalPurchasedMinutesCurrentYear(),
            'total_purchased_minutes_used' =>$stt->getTotalPaidMinutesUsageYearly(),
            'aws_current_year_minutes' => $stt->getAWSUsageYearly(),
            'gcp_current_year_minutes' => $stt->getGCPUsageYearly(),
        ];

        $chart_data['total_income'] = json_encode($payment->getPayments());
        $chart_data['total_voiceover_spending'] = json_encode($cost->getVoiceoverSpending());
        $chart_data['total_transcribe_spending'] = json_encode($cost->getTranscribeSpending());
        $chart_data['total_income_year'] = json_encode($payment->getTotalPaymentsCurrentYear());

        $percentage['income_current'] = json_encode($payment->getTotalPaymentsCurrentMonth());
        $percentage['income_past'] = json_encode($payment->getTotalPaymentsPastMonth());
        $percentage['spending_current'] = json_encode($cost->getTotalCostForTextCurrentMonth() + $cost->getTotalCostForSecondsCurrentMonth());
        $percentage['spending_past'] = json_encode($cost->getTotalCostForTextPastMonth() + $cost->getTotalCostForSecondsPastMonth());
        $percentage['purchased_current'] = json_encode($payment->getTotalPurchasedCharactersCurrentMonth());
        $percentage['purchased_past'] = json_encode($payment->getTotalPurchasedCharactersPastMonth());
        $percentage['purchased_used_current'] = json_encode($tts->getTotalPaidCharsUsageMonthly());
        $percentage['purchased_used_past'] = json_encode($tts->getTotalPaidCharsUsagePastMonth());
        $percentage['purchased_current_minutes'] = json_encode($payment->getTotalPurchasedMinutesCurrentMonth());
        $percentage['purchased_past_minutes'] = json_encode($payment->getTotalPurchasedMinutesPastMonth());
        $percentage['purchased_used_current_minutes'] = json_encode($stt->getTotalPaidMinutesUsageMonthly());
        $percentage['purchased_used_past_minutes'] = json_encode($stt->getTotalPaidMinutesUsagePastMonth());

        return view('admin.finance.dashboard.index', compact('percentage', 'chart_data', 'total_data_yearly', 'total_data_monthly'));
    }

    
    /**
     * List all user transactions
     */
    public function listTransactions(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('users.id', 'users.email', 'users.name', 'users.profile_photo_path', 'users.country', 'payments.*')->join('payments', 'payments.user_id', '=', 'users.id')->orderBy('payments.created_at', 'DESC')->get();       
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        if ($row["gateway"] == 'BankTransfer') {
                            $actionBtn = '<div>                                            
                                            <a href="'. route("admin.finance.transaction.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons edit-action-button" title="View Transaction"></i></a>
                                            <a href="'. route("admin.finance.transaction.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="Update Transaction"></i></a>
                                            <a class="deleteTransactionButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Transaction"></i></a>
                                        </div>';
                        } else {
                            $actionBtn = '<div>                                            
                                            <a href="'. route("admin.finance.transaction.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons view-action-button" title="View Transaction"></i></a>
                                            <a class="deleteTransactionButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Transaction"></i></a>
                                        </div>';
                        }

                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y, H:i:s').'</span>';
                        return $created_on;
                    })
                    ->addColumn('user', function($row){
                        if ($row['profile_photo_path']) {
                            $path = asset($row['profile_photo_path']);
                            $user = '<div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                        <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span><br><span class="text-muted">'.$row["email"].'</span></div>
                                    </div>';
                        } else {
                            $path = URL::asset('img/users/avatar.png');
                            $user = '<div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="' . $path . '"></div>
                                        <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span><br><span class="text-muted">'.$row["email"].'</span></div>
                                    </div>';
                        }
                        return $user;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-type', function($row){
                        $custom_type = '<span class="cell-box payment-'.strtolower($row["plan_type"]).'">'.ucfirst($row["plan_type"]).'</span>';
                        return $custom_type;
                    })
                    ->addColumn('custom-amount', function($row){
                        $custom_amount = '<span class="font-weight-bold">'.config('payment.default_system_currency_symbol') . $row["price"].'</span>';
                        return $custom_amount;
                    })
                    ->addColumn('custom-order', function($row){
                        $custom_order = '<span class="font-weight-bold">'.$row["order_id"].'</span>';
                        return $custom_order;
                    })
                    ->addColumn('custom-country', function($row){
                        $custom_country = '<span class="font-weight-bold">'.$row["country"].'</span>';
                        return $custom_country;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-50" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-30" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="transaction-gateway-logo" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="transaction-gateway-logo" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
                                break;
                            case 'BankTransfer':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="BankTransfer Gateway" class="transaction-gateway-logo" src="' . URL::asset('img/payments/bank-transfer.png') . '"></div>';
                                break;
                            default:
                                $custom_gateway = '<div class="overflow-hidden">Unknown</div>';
                                break;
                        }
                        
                        return $custom_gateway;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span>';
                        return $custom_status;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-amount', 'custom-plan-name', 'user', 'custom-order', 'custom-country', 'custom-gateway', 'custom-type'])
                    ->make(true);
                    
        }

        return view('admin.finance.transactions.index');
    }


    /**
     * List all user subscriptions
     */
    public function listSubscriptions(Request $request)
    {        
        if ($request->ajax()) {
            $data = Subscriber::select('subscribers.*', 'subscription_plans.plan_name', 'subscription_plans.price', 'subscription_plans.currency', 'users.name', 'users.email', 'users.profile_photo_path')->join('subscription_plans', 'subscription_plans.id', '=', 'subscribers.plan_id')->join('users', 'subscribers.user_id', '=', 'users.id')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a class="cancelSubscriptionButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-file-slash table-action-buttons delete-action-button" title="Cancel Transaction"></i></a>
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
                    ->addColumn('user', function($row){
                        if ($row['profile_photo_path']) {
                            $path = asset($row['profile_photo_path']);
                            $user = '<div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                        <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span><br><span class="text-muted">'.$row["email"].'</span></div>
                                    </div>';
                        } else {
                            $path = URL::asset('img/users/avatar.png');
                            $user = '<div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="' . $path . '"></div>
                                        <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span><br><span class="text-muted">'.$row["email"].'</span></div>
                                    </div>';
                        }
                        return $user;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_status = '<span class="cell-box subscription-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-plan-name', function($row){
                        $custom_status = '<span class="font-weight-bold">'.ucfirst($row["plan_name"]).'</span><br><span class="text-muted">'.$row["price"] . ' ' .$row['currency'].'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-characters', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.number_format($row["characters"], 0, 2).'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-minutes', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.number_format($row["minutes"], 0, 2).'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-gateway', function($row){
                        switch ($row['gateway']) {
                            case 'PayPal':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="PayPal Gateway" class="w-40" src="' . URL::asset('img/payments/paypal.svg') . '"></div>';                             
                                break;
                            case 'Stripe':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Stripe Gateway" class="w-30" src="' . URL::asset('img/payments/stripe.svg') . '"></div>';
                                break;
                            case 'Paystack':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Paystack Gateway" class="transaction-gateway-logo" src="' . URL::asset('img/payments/paystack.svg') . '"></div>';
                                break;
                            case 'Razorpay':
                                $custom_gateway = '<div class="overflow-hidden"><img alt="Razorpay Gateway" class="transaction-gateway-logo" src="' . URL::asset('img/payments/razorpay.svg') . '"></div>';
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
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-characters', 'custom-minutes', 'custom-until', 'user', 'custom-plan-name', 'custom-gateway'])
                    ->make(true);
                    
        }

        return view('admin.finance.transactions.subscriptions');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $id)
    {
        $user = User::where('id', $id->user_id)->first();

        return view('admin.finance.transactions.show', compact('id', 'user'));     
    }


    /**
     * Edit the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $id)
    {
        $user = User::where('id', $id->user_id)->first();

        return view('admin.finance.transactions.edit', compact('id', 'user'));     
    }


    /**
     * Update the specified resource - bank transfer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Payment $id)
    {
        request()->validate([
            'payment-status' => 'required',
        ]);

        $id->status = request('payment-status');
        $id->save();


        if ($id->status == 'completed') {

            $user = User::where('id', $id->user_id)->first();
            $group = ($user->hasRole('admin'))? 'admin' : 'subscriber';
            
            $user->syncRoles($group);    
            $user->group = $group;

            if ($id->plan_type == 'subscription') {
                $user->plan_id = $id->plan_id;
                $user->available_chars = $id->characters;
                $user->available_minutes = $id->minutes;
                $user->save();   
                    
                $subscription = Subscriber::where('subscription_id', $id->order_id)->firstOrFail();
                $subscription->status = 'Active';
                $subscription->save();
            } else {
                $user->available_chars_prepaid = ($user->available_chars_prepaid + $id->characters);
                $user->available_minutes_prepaid = ($user->available_minutes_prepaid + $id->minutes);
                $user->save();  
            }
            
            
        }

        return redirect()->route('admin.finance.transactions')->with('success', __('Bank Transfer transaction has been updated successfully'));     
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {  
        if ($request->ajax()) {

            $payment = Payment::find(request('id'));

            if($payment) {

                $payment->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        }         
    }
}
