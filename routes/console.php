<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rappels cours particuliers (toutes les minutes)
Schedule::command('app:send-private-lesson-reminders')
    ->everyMinute()
    ->withoutOverlapping();

// Backup DB chaque nuit à 2h
// withoutOverlapping() : verrou atomique empêchant deux dumps concurrents
// (ex. si schedule:run est lancé par plusieurs crons) → évite les dumps vides.
Schedule::command('backup:run')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onFailure(function () {
        Log::critical('Backup échoué !');
    });

// Nettoyage des vieux backups à 3h
Schedule::command('backup:clean')
    ->dailyAt('03:00')
    ->withoutOverlapping();
