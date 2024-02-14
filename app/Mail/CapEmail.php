<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CapEmail extends Mailable {
    use Queueable, SerializesModels;

    public $html;
    public $content;

    /**
    * Create a new message instance.
    *
    * @return void
    */

    public function __construct( $html, $content ) {
        $this->html = $html;
        $this->content = $content;
    }

    /**
    * Build the message.
    *
    * @return $this
    */

    public function build() {
        $subject = 'PH TA Capacity File - as of ' . date( 'Y-m-d' );
        return $this->from( 'TA.Insights@vxi.com' )
        ->subject( $subject )
        ->view( 'email' )
        ->with( [
            'html' => $this->html,
            'content' => $this->content,
        ] );
    }

}
