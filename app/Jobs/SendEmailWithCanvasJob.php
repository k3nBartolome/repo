<?php

namespace App\Jobs;

use App\Mail\CanvasEmail;
use App\Services\CanvasGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailWithCanvasJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(CanvasGenerator $canvasGenerator)
    {
        try {
            // Generate canvas
            $canvasData = $canvasGenerator->generate();

            // Send email
            Mail::to('recipient@example.com')
                ->send(new CanvasEmail($canvasData));
        } catch (\Exception $e) {
            Log::error('Failed to send email with canvas: '.$e->getMessage());
        }
    }

    public function release($seconds = 0)
    {
        // Release the job back to the queue after 5 minutes
        return now()->addMinutes(5)->isPast();
    }
}
