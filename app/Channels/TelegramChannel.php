<?php

namespace App\Channels;

use App\Facades\Bot;
use Illuminate\Notifications\Notification;

class TelegramChannel 
{
    public function send(object $notifiable, Notification $notification): void
    {
        $message = Bot::sendMessage($notification->toTelegram($notifiable));
    }
}
