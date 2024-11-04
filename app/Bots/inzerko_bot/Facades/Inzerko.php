<?php

namespace App\Bots\inzerko_bot\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\URL;

/**
 * Facade for \Romanlazko\Telegram\App\Bot
 * @param \Romanlazko\Telegram\App\BotApi $api
 * @see \Romanlazko\Telegram\App\Bot
 */
class Inzerko extends Facade
{
    protected static function getFacadeAccessor()
    {
        if (! app()->environment('production')) {
            return 'pozorbottestbot';
        }
        return 'inzerko';
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();

        if (method_exists($instance, $method)) {
            return $instance->$method(...$arguments);
        }
        
        return $instance::$method(...$arguments);
    }
}