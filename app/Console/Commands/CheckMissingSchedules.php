<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\MissingScheduleNotification;
use Illuminate\Support\Facades\Log;

class CheckMissingSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-missing-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */public function handle()
{
    $today = now()->format('Y-m-d');

    $users = \App\Models\User::all();

    foreach ($users as $user) {
        $hasSchedule = $user->schedules()
            ->whereDate('start_time', $today)
            ->exists();

        if (!$hasSchedule) {
            Log::info("通知送信: " . $user->email);
            // 投稿がなければ通知を送信
            \Notification::route('mail', $user->email)
                ->notify( new MissingScheduleNotification($user));
        }
    }
}
}
