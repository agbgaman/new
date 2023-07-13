<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompaignMailingNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $request;
    private $compaignMailId;
    private $userEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request,$compaignMailId,$userEmail)
    {
        $this->request        = $request;
        $this->compaignMailId = $compaignMailId;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        $baseUrl = 'https://dash.gts.ai'; // Replace with your base URL
        $trackingUrl = $baseUrl . '/track-open?email_campaign_id=' . $this->compaignMailId . '&user_email=' . urlencode($this->userEmail);

        return $this->subject('Notification message ' . config('app.name'))
            ->markdown('emails.campaign-mailing', [
                'userEmail' => $this->userEmail,
                'emailBody' => $this->request['description'],
                'footer' => $this->request['footer'],
                'compaignMailId' => $this->compaignMailId,
                'trackingUrl' => $trackingUrl,
            ]);
    }

}
