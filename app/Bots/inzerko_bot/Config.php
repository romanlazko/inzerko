<?php

namespace App\Bots\inzerko_bot;

use Illuminate\Support\Facades\Log;

class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'telegram_token'    => null,
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
        ];
    }
}
