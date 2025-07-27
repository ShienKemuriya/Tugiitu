<?php

namespace App\Console\Commands;

use App\Models\Schedule; // スケジュールモデル
use App\Models\User;     // ユーザーモデル
use App\Models\Follow;    // フォローモデル (必要であれば)
use App\Notifications\EventReminderNotification; // 通知クラス
use Carbon\Carbon;        // 日付操作

use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'まもなく配信を開始するスケジュールに対し、フォロワーへ通知を送信する';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $this->info('handle()の呼び出し'); 

        $now = Carbon::now();
        $soon = $now->copy()->addMinutes(10); // 10分以内の配信を対象

        $this->info("現在の時刻: {$now->format('Y-m-d H:i:s')}");
        $this->info("検索終了時刻 (10分後): {$soon->format('Y-m-d H:i:s')}");

        // 通知していない && 10分以内に始まるスケジュールを取得
        $schedules = Schedule::where('is_notification', false)
            ->whereBetween('start_time', [$now, $soon])
            ->get();

        $this->info("取得したスケジュールの数: {$schedules->count()}"); // 0なら、スケジュールが見つかっていない

        if ($schedules->isEmpty()) {
            $this->info('通知対象のスケジュールは見つかりませんでした。');
        }

        foreach ($schedules as $schedule) {
            $this->info("スケジュールID: {$schedule->id}, タイトル: {$schedule->title}, 開始時刻: {$schedule->start_time} を処理中。");

            $streamer = $schedule->user; // Scheduleモデルに user() リレーションが定義されていること

            if (!$streamer) {
                $this->warn("ID {$schedule->id} のスケジュールに対応するライバー（ユーザー）が見つかりません。スキップします。");
                continue;
            }
            $this->info("ライバー: {$streamer->name} (ID: {$streamer->id})");


            $followers = $streamer->followers; // Userモデルに followers() リレーションが定義されていること

            if ($followers->isEmpty()) {
                $this->info("ライバー {$streamer->name} をフォローしているユーザーは見つかりませんでした。スキップします。");
                continue;
            }
            $this->info("ライバー {$streamer->name} のフォロワー数: {$followers->count()}");

            foreach ($followers as $follower) {
                if (empty($follower->email)) {
                    $this->warn("フォロワーID: {$follower->id} (名前: {$follower->name}) にメールアドレスが設定されていません。スキップします。");
                    continue;
                }
                $this->info("フォロワーID: {$follower->id} (名前: {$follower->name}, メール: {$follower->email}) へ通知を試行します。");

                $follower->notify(new EventReminderNotification($schedule, $streamer));
                $this->info("フォロワー {$follower->name} への通知キューイングまたは送信成功。");
            }

            // 通知済みフラグを更新して保存
            $schedule->is_notification = true; 
            $schedule->save();
            $this->info("スケジュールID {$schedule->id} の is_notification を true に更新しました。");

            $this->info("スケジュールID {$schedule->id} に対して通知処理を完了しました。");
        }

        $this->info('通知処理が完了しました。');
    }
}
