<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Foundation\Configuration\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// return function (Schedule $schedule) {
//     $schedule->command('notify:schedules')->everyMinute();
// };

