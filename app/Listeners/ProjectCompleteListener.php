<?php

namespace App\Listeners;

use App\Events\PayoutRequested;
use App\Events\ProjectCompleteEvent;
use App\Models\User;
use App\Notifications\PayoutRequestNotification;
use App\Notifications\ProjectCompleteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class ProjectCompleteListener
{
    /**
     * Handle the event.
     *
     * @param  PayoutRequested  $event
     * @return void
     */
    public function handle(ProjectCompleteEvent $event)
    {
        $admins = User::role('admin')->get();

        Notification::send($admins, new ProjectCompleteNotification($event->user,$event->subject));
    }
}
