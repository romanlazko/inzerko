<?php

namespace App\Bots\inzerko_bot\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

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
        $this->locale($notifiable->locale);
        
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return $this->buildMailMessage($verificationUrl);
    }

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(__('notification.verify_telegram_connection.subject'))
            ->line(__('notification.verify_telegram_connection.line_1'))
            ->action(__('notification.verify_telegram_connection.action'), $url)
            ->line(__('notification.verify_telegram_connection.line_2'));
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'inzerko_bot.verify.telegram.connection',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'telegram_chat_id' => $this->telegram_chat_id,
                'token' => sha1($this->getAccessToken($notifiable)),
            ]
        );
    }

    protected function getAccessToken($notifiable)
    {
        return $notifiable->createAccessToken('verify-telegram-connection')->token;
    }
}
