<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailingNotifications extends Mailable
{
    use Queueable, SerializesModels;

    private $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Notification message ' . config('app.name'))
            ->markdown('emails.mailing-notification', [
                'user' => 'usama',
                'emailBody' => $this->request['description'],
                'footer' => $this->request['footer'],
            ]);

    }
}
