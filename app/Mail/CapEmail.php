<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CapEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $html;
    public $message;

    public function __construct($html, $message)
    {
        $this->html = $html;
        $this->message = $message;
    }

    public function build()
    {
        $subject = 'PH TA Capacity File - as of ' . date('Y-m-d');
        return $this->view('email', [
            'html' => $this->html,
            'message' => $this->message,
        ])
            ->with([
                'html' => $this->html,
                'message' => $this->message,
            ])
            ->subject($subject);
    }
}
