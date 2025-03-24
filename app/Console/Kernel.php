<?php

namespace App\Console;

use App\Models\EventReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $events = EventReminder::whereDate('event_date', now()->addDay()->toDateString())->get();

            foreach ($events as $event) {
                if (!empty($event->emails)) {
                    foreach (json_decode($event->emails) as $email) {
                        Mail::to($email)->send(new EventReminderMail($event));
                    }
                }
            }
        // })->dailyAt('19:20');
    })->everyFiveMinutes();

        $schedule->call(function () {
            Http::withoutVerifying()->get(config('app.url') . '/sync-events');
        })->everyMinute();
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
