<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissingScheduleNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $streamer;

    /**
     * Create a new notification instance.
     */
    public function __construct($streamer)
    {
        $this->streamer = $streamer;
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
        $data = [
            'notifiable' => $notifiable,
            'streamer' => $this->streamer,
        ];

        return (new MailMessage)
            ->subject('配信スケジュールの投稿をお忘れではありませんか？')
            ->view('emails.missing_schedule', $data);
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
