<?php

namespace App\Notifications;

use App\Channels\CustomEmailChannel;
use Illuminate\Notifications\Notification;

class CustomEmailNotification extends Notification
{
    public function toCustomEmail($notifiable)
    {
        // Define the custom email message here
        return [
            'email' => 'custom@example.com',
            'subject' => 'Custom Email Subject',
            'body' => 'Custom Email Body',
        ];
    }

    public function via($notifiable)
    {
        return [CustomEmailChannel::class]; // Use the custom email channel
    }
}
