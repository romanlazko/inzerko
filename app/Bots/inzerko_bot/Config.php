<?php

namespace App\Bots\inzerko_bot;


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
