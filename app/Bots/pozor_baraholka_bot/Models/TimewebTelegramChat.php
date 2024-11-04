<?php

namespace App\Bots\pozor_baraholka_bot\Models;

use Romanlazko\Telegram\Models\TelegramChat;

class TimewebTelegramChat extends TelegramChat
{
    protected $connection = 'timeweb_mysql';
}
