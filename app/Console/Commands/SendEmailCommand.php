<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CapEmailController;
use Illuminate\Http\Request;

class SendEmailCommand extends Command
{
    protected $signature = 'emails:generate-and-send';
    protected $description = 'Generate HTML content and send emails';

    public function handle()
    {
        // Instantiate CapEmailController to access its methods
        $emailController = new CapEmailController();

        // Generate HTML content
        $htmlContent = $emailController->generateHtmlContent(new Request());

        // Send email with the generated HTML content
        $emailController->sendEmail(new Request());

        $this->info('HTML content generated and emails sent successfully.');
    }
}