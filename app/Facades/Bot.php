<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\URL;

/**
 * Facade for \Romanlazko\Telegram\App\Bot
 * @param \Romanlazko\Telegram\App\BotApi $api
 * @see \Romanlazko\Telegram\App\Bot
 */
class Bot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bot';
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();
        
        return $instance::$method(...$arguments);
    }
}
