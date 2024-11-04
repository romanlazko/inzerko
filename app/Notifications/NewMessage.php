<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Romanlazko\Telegram\Channels\TelegramChannel;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $thread)
    {
        $this->announcement = $thread->announcement;
        $this->message = $thread->messages->last()->message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $this->locale($notifiable->locale);
        
        return $notifiable->chat ? ['mail', TelegramChannel::class] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject(__('notification.new_message.subject'))
                    ->line(__('notification.new_message.line_1'))
                    ->line("**{$this->announcement->title}**")
                    ->line($this->message)
                    ->action(__('notification.new_message.action'), route('home'))
                    ->line(__('notification.new_message.line_2'));
    }

    public function toTelegram(object $notifiable)
    {
        $text = implode("\n", [
            __('notification.new_message.line_1') ."\n",
            "*{$this->announcement->title}*"."\n",
            "{$this->message}"."\n",
            "[" . __('notification.new_message.action') . "](" . route('home') . ")"
        ]);

        return [
            'text'                      => $text,
            'chat_id'                   => $notifiable->chat->chat_id,
            'disable_web_page_preview'  => 'true',
            'parse_mode'                => 'Markdown',
        ];
    }

    public function shouldSend(object $notifiable): bool
    {
        $unreadMessagesCount = $this->thread->messages()
            ->where('read_at', null)
            ->where('user_id', '!=', $notifiable->id)
            ->count();

        return $unreadMessagesCount > 0;
    }
}
