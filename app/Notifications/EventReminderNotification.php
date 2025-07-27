<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailables\Content;
class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $event;
    protected $streamer;
    /**
     * Create a new notification instance.
     */
    public function __construct($event, $streamer)
    {
        $this->event = $event;//配信予定を格納
        $this->streamer = $streamer;//配信者の情報
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Bladeテンプレートに渡すデータを配列で定義します
        $data = [
            'notifiable' => $notifiable, // toMailの引数
            'event' => $this->event,     // コンストラクタで受け取ったイベント（スケジュール）
            'streamer' => $this->streamer, // コンストラクタで受け取ったライバー
            // 'post_url' => url("/post/{$this->event->id}") // テンプレートで直接url()を呼ぶので不要だが、あっても害はない
        ];
        
        return (new MailMessage)
            //メールコンポーネントを使った送信    
            // ->subject('配信のリマインダー通知')
            // ->greeting("こんにちは、{$notifiable->name} さん")
            // ->line("「{$this->streamer->name}」さんの配信がまもなく始まります。")
            // ->line("配信タイトル：{$this->event->title}")
            // ->line("開始時刻：{$this->event->start_time->format('Y年m月d日 H:i')}")
            // ->action('配信詳細を見る', url("/post/{$this->event->id}"))
            // ->line('引き続きアプリをお楽しみください！');

            //Bladeテンプレートからメール送信
            //カスタマイズ性が高いのでこちらを採用
            ->subject('配信のリマインダー通知')
            ->view('emails.event_reminder', $data);
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
