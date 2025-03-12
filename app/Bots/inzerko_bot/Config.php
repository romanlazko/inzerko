<?php

namespace App\Bots\inzerko_bot;

use Illuminate\Support\Facades\Log;

class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'languages'    => null,
                'contact_type' => null,
                'temp'         => null,
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
        ];
    }
}
