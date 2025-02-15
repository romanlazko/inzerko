<?php

namespace App\Notifications;

use App\Bots\inzerko_bot\Channels\InzerkoChannel;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Bots\inzerko_bot\Commands\UserCommands\CreateAnnouncement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Romanlazko\Telegram\Models\TelegramChat;

class VerificationSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $this->locale($notifiable->locale);

        return $notifiable->chat ? ['mail', InzerkoChannel::class] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('home');

        return (new MailMessage)
            ->subject(__('notification.verification_success.subject'))
            ->line(__('notification.verification_success.line_1'))
            ->action(__('notification.verification_success.action'), $url)
            ->line(__('notification.verification_success.line_2'));
    }

    public function toTelegram(TelegramChat $notifiable)
    {
        $text = implode("\n", [
            __('notification.verification_success.line_1')
        ]);
        
        $buttons = Inzerko::inlineKeyboard([
            [array(CreateAnnouncement::getTitle('ru'), CreateAnnouncement::$command, '')],
        ]);

        return Inzerko::sendMessage([
            'text'                      => $text,
            'chat_id'                   => $notifiable?->chat_id,
            'reply_markup'              => $buttons,
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => 'true',
            'parse_mode'                => 'Markdown',
        ]);
    }
}
