<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// REQ-MM-04: expire overdue member_packages daily and sync members.status
Schedule::command('memberships:expire')->dailyAt('00:00');
