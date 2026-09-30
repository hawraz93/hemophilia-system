<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requires the scheduler to run: `php artisan schedule:work` or a cron / Task Scheduler entry for `php artisan schedule:run`
Schedule::command('app:evaluate-patient-statuses')->dailyAt('01:00');
Schedule::command('app:backup-database')->dailyAt('02:00');
