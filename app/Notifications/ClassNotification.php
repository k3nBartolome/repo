<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClassNotification extends Notification
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Custom Email Subject')
            ->line('This is a custom email notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toCustomEmail($notifiable)
    {
        return [
            'email' => $this->email,
            'subject' => 'Custom Email Subject',
            'body' => 'Custom Email Body',
        ];
    }

    public function via($notifiable)
    {
        return ['mail', 'custom-email'];
    }
}
