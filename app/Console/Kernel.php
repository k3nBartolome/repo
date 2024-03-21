<?php

namespace App\Console;

use App\Http\Controllers\CapEmailController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $controller = new CapEmailController();
            $controller->sendEmail(new Request());
        })->dailyAt('19:56')->timezone('Asia/Manila');
        $schedule->call(function () {
            $controller = new CapEmailController();
            $controller->sendSR(new Request());
        })->dailyAt('17:40')->timezone('Asia/Manila');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
