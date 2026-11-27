<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ============================================================================
// SCHEDULED TASKS
// ============================================================================

// TEST MODE: Send report every 2 minutes (for testing)
// Uncomment this line when testing, then comment it out when switching to production
Schedule::command('report:send-monthly --test')
    ->everyTwoMinutes()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/monthly-report.log'));

// PRODUCTION MODE: Send monthly report on 1st of every month at 00:00 (midnight)
// Comment this out during testing, then uncomment when ready for production
// Schedule::command('report:send-monthly')
//     ->monthlyOn(1, '00:00')
//     ->timezone('Asia/Manila')
//     ->withoutOverlapping()
//     ->appendOutputTo(storage_path('logs/monthly-report.log'));
