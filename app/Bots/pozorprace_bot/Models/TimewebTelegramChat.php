<?php

namespace App\Bots\pozorprace_bot\Models;

use Romanlazko\Telegram\Models\TelegramChat;

class TimewebTelegramChat extends TelegramChat
{
    protected $connection = 'timeweb_mysql';

    protected $table = 'telegram_chats';
}
