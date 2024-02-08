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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($html)
    {
        $this->html = $html;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email')
                    ->with([
                        'html' => $this->html,
                    ]);
    }
}
