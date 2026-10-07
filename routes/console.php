<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// C&M group report mail: checked every 5 minutes, sends once in each admin-chosen Kolkata hour
\Illuminate\Support\Facades\Schedule::command('report:send-group-mail')->everyFiveMinutes()->withoutOverlapping();
