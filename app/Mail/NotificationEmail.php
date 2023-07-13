<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$notification)
    {
        $this->user = $user;
        $this->notification = $notification;

    }

    public function build()
    {
        return $this->subject($this->notification['type'].' Notification message ' . config('app.name'))
            ->markdown('emails.notification', [
                'user' => $this->user,
                'notification' => $this->notification,
            ]);

    }

}
