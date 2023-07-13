<?php

namespace App\Listeners;

use App\Events\ProjectPermissionEvent;
use App\Models\Project;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Notifications\ProjectCompleteNotification;
use App\Notifications\ProjectPermissionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class ProjectPermissionListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProjectPermissionEvent $event)
    {
        $user  = User::where('id',$event->user->id)->get();

        $notification = [
            'type'      => 'project-permission',
            'action'    => 'Info',
            'subject'   => 'Project Permission',
            'message'   => $event->subject,
            'user'      => $user,
            'user_id'   => \auth()->user()->id,
        ];
        Notification::send($user, new GeneralNotification($notification));
    }
}
