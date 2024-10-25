<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class VerifyTelegramConnection extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $telegram_chat_id)
    {
        //
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
        $url = URL::temporarySignedRoute(
            'verify.telegram.connection',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'telegram_chat_id' => $this->telegram_chat_id,
                'telegram_token' => sha1($notifiable->telegram_token),
            ]
        );
        
        return (new MailMessage)
            ->subject(__('notification.verify_telegram_connection.subject'))
            ->line(__('notification.verify_telegram_connection.line_1'))
            ->action(__('notification.verify_telegram_connection.action'), $url)
            ->line(__('notification.verify_telegram_connection.line_2'));
    }
}
