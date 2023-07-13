<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\PaymentPlatform;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use App\Models\PrepaidPlan;

class PricingPlanController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monthly = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'monthly')->count();
        $yearly = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'yearly')->count();
        $prepaid = PrepaidPlan::where('status', 'active')->count();

        $monthly_subscriptions = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'monthly')->get();
        $yearly_subscriptions = SubscriptionPlan::where('status', 'active')->where('pricing_plan', 'yearly')->get();
        $prepaids = PrepaidPlan::where('status', 'active')->get();

        return view('user.plans.index', compact('monthly', 'yearly', 'prepaid', 'monthly_subscriptions', 'yearly_subscriptions', 'prepaids'));
    }


    /**
     * Checkout for Subscription plans only.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(SubscriptionPlan $id)
    {           
        $payment_platforms = PaymentPlatform::where('enabled', 1)->get();

        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;

        $total_value = $tax_value + $id->price;
        $currency = $id->currency;
        $gateway_plan_id = $id->gateway_plan_id;

        $bank_information = ['bank_instructions', 'bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $bank_order_id = 'BT-' . strtoupper(Str::random(10));
        session()->put('bank_order_id', $bank_order_id);

        return view('user.plans.subscribe-checkout', compact('id', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'gateway_plan_id', 'bank', 'bank_order_id'));
    } 


    /**
     * Checkout for Prepaid plans only.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkout(PrepaidPlan $id)
    {   
        $payment_platforms = PaymentPlatform::where('enabled', 1)->get();
        
        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;

        $total_value = $tax_value + $id->price;
        $currency = $id->currency;

        $bank_information = ['bank_instructions', 'bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $bank_order_id = 'BT-' . strtoupper(Str::random(10));
        session()->put('bank_order_id', $bank_order_id);
        
        return view('user.plans.prepaid-checkout', compact('id', 'payment_platforms', 'tax_value', 'total_value', 'currency', 'bank', 'bank_order_id'));
    }
}
