<?php

namespace App\Services;

use App\Traits\ConsumesExternalServiceTrait;
use App\Events\PaymentReferrerBonus;
use Illuminate\Http\Request;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Models\PrepaidPlan;
use App\Models\Subscriber;
use App\Models\SubscriptionPlan;
use App\Models\Setting;

class BankTransferService 
{
    use ConsumesExternalServiceTrait;

    public function handlePaymentSubscription(Request $request, SubscriptionPlan $id)
    {   
        if (session()->has('bank_order_id')) {
            $orderID = session()->get('bank_order_id');
            session()->forget('bank_order_id');
        }

        $duration = ($id->pricing_plan == 'monthly') ? 30 : 365;

        $subscription = Subscriber::create([
            'active_until' => now()->addDays($duration),
            'user_id' => auth()->user()->id,
            'plan_id' => $id->id,
            'status' => 'Pending',
            'created_at' => now(),
            'gateway' => 'BankTransfer',
            'plan_name' => $id->plan_name,
            'characters' => $id->characters,
            'minutes' => $id->minutes,
            'subscription_id' => $orderID,
        ]);

        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;
        $total_price = $tax_value + $id->price;

        if (config('payment.referral.payment.enabled') == 'on') {
            if (config('payment.referral.payment.policy') == 'first') {
                if (Payment::where('user_id', auth()->user()->id)->where('status', 'Success')->exists()) {
                    /** User already has at least 1 payment and referrer already received credit for it */
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $orderID, $total_price, 'BankTransfer'));
                }
            } else {
                event(new PaymentReferrerBonus(auth()->user(), $orderID, $total_price, 'BankTransfer'));
            }
        }

        $record_payment = new Payment();
        $record_payment->user_id = auth()->user()->id;
        $record_payment->plan_id = $id->id;
        $record_payment->plan_type = 'prepaid';
        $record_payment->order_id = $orderID;
        $record_payment->plan_type = 'subscription';
        $record_payment->plan_name = $id->plan_name;
        $record_payment->frequency = $id->pricing_plan;
        $record_payment->price = $total_price;
        $record_payment->currency = $id->currency;
        $record_payment->gateway = 'BankTransfer';
        $record_payment->status = 'pending';
        $record_payment->characters = $id->characters;
        $record_payment->minutes = $id->minutes;
        $record_payment->save();      

        event(new PaymentProcessed(auth()->user()));

        $bank_information = ['bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $plan_type = 'subscription';

        return view('user.plans.banktransfer-success', compact('id', 'orderID', 'bank', 'total_price', 'plan_type'));
    }


    public function handlePaymentPrePaid(Request $request, PrepaidPlan $id)
    {   
        $tax_value = (config('payment.payment_tax') > 0) ? $tax = $id->price * config('payment.payment_tax') / 100 : 0;
        $total_value = $tax_value + $id->price;

        $final_price = $total_value;

        if (session()->has('bank_order_id')) {
            $orderID = session()->get('bank_order_id');
            session()->forget('bank_order_id');
        }

        if (config('payment.referral.payment.enabled') == 'on') {
            if (config('payment.referral.payment.policy') == 'first') {
                if (Payment::where('user_id', auth()->user()->id)->where('status', 'Success')->exists()) {
                    /** User already has at least 1 payment and referrer already received credit for it */
                } else {
                    event(new PaymentReferrerBonus(auth()->user(), $orderID, $final_price, 'BankTransfer'));
                }
            } else {
                event(new PaymentReferrerBonus(auth()->user(), $orderID, $final_price, 'BankTransfer'));
            }
        }

        $record_payment = new Payment();
        $record_payment->user_id = auth()->user()->id;
        $record_payment->order_id = $orderID;
        $record_payment->plan_id = $id->id;
        $record_payment->plan_type = 'prepaid';
        $record_payment->plan_name = $id->plan_name;
        $record_payment->price = $final_price;
        $record_payment->frequency = 'one time';
        $record_payment->currency = $id->currency;
        $record_payment->gateway = 'BankTransfer';
        $record_payment->status = 'pending';
        $record_payment->characters = $id->characters;
        $record_payment->minutes = $id->minutes;
        $record_payment->save();
             
        event(new PaymentProcessed(auth()->user()));

        $bank_information = ['bank_requisites'];
        $bank = [];
        $settings = Setting::all();

        foreach ($settings as $row) {
            if (in_array($row['name'], $bank_information)) {
                $bank[$row['name']] = $row['value'];
            }
        }

        $plan_type = 'prepaid';

        return view('user.plans.banktransfer-success', compact('id', 'orderID', 'bank', 'final_price', 'plan_type'));
    }

}