<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CapEmailController;
use Illuminate\Http\Request;

class SendEmailCommand extends Command {
    protected $signature = 'emails:generate-and-send';
    protected $description = 'Generate HTML content and send emails';

    public function handle() {
        $emailController = new CapEmailController();
        $emailController->sendEmail( new Request() );
        $this->info( 'Emails sent successfully.' );
    }
}
