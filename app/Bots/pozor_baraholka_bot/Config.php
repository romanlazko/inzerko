<?php

namespace App\Bots\pozor_baraholka_bot;


class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'city'              => null,
                'type'              => null,
                'next'              => null,
                'condition'         => null,
                'category_id'       => null,
                'subcategory_id'    => null,
                'announcement_id'   => null,
            ],
            'brno'      => '@pozor_baraholka',
            'prague'    => '@baraholkaprague',
            'admin_ids'         => [
            ],
            'bot_username' => 'pozor_baraholka_bot'
        ];
    }
}
