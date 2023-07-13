<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\Subscriber;

class HelperService 
{
    public static function getCharactersLeft()
    {   
        return auth()->user()->available_chars;
    }

    public static function getMinutesLeft()
    {   
        return auth()->user()->available_minutes;
    }

    public static function getMinutesPercentage()
    {   
        if (auth()->user()->plan_id) {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->pluck('minutes');
            $percentage = (auth()->user()->available_minutes * 100) / $plan[0];
            return $percentage;
        } else {
            $percentage = (auth()->user()->available_minutes * 100) / config('stt.free_minutes');
            return $percentage;
        }
    }

    public static function getCharactersPercentage()
    {   
        if (auth()->user()->plan_id) {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->pluck('characters');
            $percentage = (auth()->user()->available_chars * 100) / $plan[0];
            return $percentage;
        } else {
            $percentage = (auth()->user()->available_chars * 100) / config('tts.free_chars');
            return $percentage;
        }
    }

    public static function getRenewalCycle()
    {   
        if (auth()->user()->plan_id) {
            $plan = SubscriptionPlan::where('id', auth()->user()->plan_id)->pluck('pricing_plan');

            return ucfirst($plan[0]);
        } else {
            return __('No Repeats');
        }
    }

    public static function getRenewalDate()
    {   
        // if (auth()->user()->plan_id) {
        //     $plan = Subscriber::where('user_id', auth()->user()->id)->get();
        //     return date_format($plan->active_until, 'd.m.Y');
        // } else {
        //     return 'No Repeats';
        // }
    }
}