<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPlan;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionCheckTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscription statuses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check subscription status, block the ones that missed payments.
     *
     * @return int
     */
    public function handle()
    {
        # Get all active subscriptions
        $subscriptions = Subscriber::where('status', 'Active')->where('gateway', '<>', 'FREE')->get();

        foreach($subscriptions as $row) {

            # Give extra 2 days to make a payment, otherwise suspend subscription
            # and move the user to free tier
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $row->active_until);
            $date = $date->addDays(2);

            $result = Carbon::createFromFormat('Y-m-d H:i:s', $date)->isPast();

            if ($result) {
                $row->update([
                    'status'=>'Expired'
                ]);

                $user = User::where('id', $row->user_id)->firstOrFail();
                $group = ($user->hasRole('admin'))? 'admin' : 'user';
                if ($group == 'user') {
                    $user->syncRoles($group);
                    $user->group                = $group;
                    $user->plan_id              = null;
                    $user->available_chars      = 0;
                    $user->available_minutes    = 0;
                    $user->save();
                } else {
                    $user->syncRoles($group);
                    $user->group                = $group;
                    $user->plan_id              = null;
                    $user->save();
                }
            }
        }

        # Check status of Free Subscription plans and renew accordingly
        $free_subscriptions = Subscriber::where('status', 'Active')->where('gateway', 'FREE')->get();

        foreach($free_subscriptions as $subscription) {
            if ($subscription->active_until < now()) {

                $user = User::where('id', $subscription->user_id)->first();
                $plan = SubscriptionPlan::where('id', $subscription->plan_id)->first();

                $duration = ($plan->pricing_plan == 'monthly') ? 30 : 365;

                # Check if user still exists
                if ($user !== null) {

                    # Check if plan still exits
                    if ($plan !== null) {

                        $subscription->update(['active_until' => Carbon::now()->addDays($duration)]);
                        $user->available_chars = $subscription->characters;
                        $user->available_minutes = $subscription->minutes;
                        $user->save();
                    }

                }

            }
        }
    }
}
