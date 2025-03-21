<?php

namespace App\Bots\inzerko_bot\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Romanlazko\Telegram\App\TelegramLogDb;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Models\TelegramChat;

class InzerkoChannel 
{
    public function send(object $notifiable, Notification $notification): void
    {
        $notifiable = $this->srializeNotifiable($notifiable);

        try {
            $notification->toTelegram($notifiable);
        }
        catch (TelegramException|\Exception|\Throwable|\Error $exception) {
            TelegramLogDb::report($notifiable?->bot?->id, $exception);
        }
    }

    private function srializeNotifiable(object $notifiable)
    {
        if ($notifiable instanceof TelegramChat) {
            return $notifiable;
        }

        if ($notifiable->chat instanceof TelegramChat) {
            return $notifiable->chat;
        }
        
        throw new Exception("Notifiable must be an instance of TelegramChat");
    }
}
