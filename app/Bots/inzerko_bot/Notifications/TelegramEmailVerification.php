<?php

namespace App\Bots\inzerko_bot\Notifications;


use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class TelegramEmailVerification extends Notification implements ShouldQueue
{
    use Queueable;

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

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        
        return $this->buildMailMessage($verificationUrl);
    }

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(__('notification.verify_email.subject'))
            ->line(__('notification.verify_email.line_1'))
            ->action(__('notification.verify_email.action'), $url)
            ->line(__('notification.verify_email.line_2'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'inzerko_bot.verify', 
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)), 
            [
                'token' => $notifiable->createAccessToken('telegram-email-verification')->token,
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
