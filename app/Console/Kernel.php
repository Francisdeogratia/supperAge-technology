<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
    \App\Console\Commands\DeleteExpiredTales::class,
    Commands\CheckStoreSubscriptions::class,
];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
{
    $schedule->command('tales:delete-expired')->daily();
    // CLEANUP OLD TALES (Mismatched: Changed from cleanup:oldtales)
    $schedule->command('app:clean-up-old-tales')->daily(); // <-- CORRECTED NAME

    // CLEANUP OLD COMMENTS (Mismatched: Changed from cleanup:oldcomments)
    $schedule->command('app:clean-up-old-comments')->hourly(); // <-- CORRECTED NAME
     $schedule->command('badge:renew')->daily();


     // Check subscriptions daily at midnight
        $schedule->command('marketplace:check-subscriptions')
                 ->daily()
                 ->at('00:00')
                 ->timezone('Africa/Lagos')
                 ->emailOutputOnFailure(env('ADMIN_EMAIL'));

        // You can also run it multiple times a day
        // Run every 6 hours to catch expiries quickly
        $schedule->command('marketplace:check-subscriptions')
                 ->cron('0 */6 * * *')
                 ->timezone('Africa/Lagos')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Clean up old notifications (optional)
        $schedule->call(function () {
            \App\Models\Notification::where('created_at', '<', now()->subMonths(3))
                ->where('read_notification', 'yes')
                ->delete();
        })->weekly();


        // Check for viral posts every hour
    $schedule->command('posts:check-viral')->hourly();
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



