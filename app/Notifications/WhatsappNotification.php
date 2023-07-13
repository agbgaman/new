<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;


class WhatsappNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function toWhatsapp($notifiable)
    {
        $fromPhoneNumberId = env('FROM_PHONE_NUMBER_ID');
        $accessToken = env('ACCESS_TOKEN');
        $toPhoneNumber = $notifiable->routeNotificationFor('whatsapp');

        $client = new Client();

        $response = $client->post("https://graph.facebook.com/v16.0/{$fromPhoneNumberId}/messages", [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $toPhoneNumber,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $this->message,
                ],
            ],
        ]);

        return $response;
    }
}
