<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendImageMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function build()
    {
        return $this->subject('Component Image')
                    ->view('emails.send-image');
    }
}
