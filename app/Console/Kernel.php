<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Rappels cours particuliers (toutes les minutes)
        $schedule->command('app:send-private-lesson-reminders')
            ->everyMinute()
            ->withoutOverlapping();

        // Backup complet chaque nuit à 2h (DB + fichiers uploadés)
        $schedule->command('backup:run')
            ->dailyAt('02:00')
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::critical('Backup échoué !');
            });

        // Nettoyage des vieux backups chaque nuit à 3h (garde 7 jours)
        $schedule->command('backup:clean')
            ->dailyAt('03:00');
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
