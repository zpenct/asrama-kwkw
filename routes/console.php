<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// TODO: set cron job
// Schedule::command(\App\Console\Commands\ExpireBookings::class)
//     ->everyTenMinutes();
