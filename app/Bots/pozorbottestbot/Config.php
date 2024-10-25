<?php

namespace App\Bots\pozorbottestbot;


class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'city'              => null,
                'type'              => null,
                'count'             => null,
                'condition'         => null,
                'category_id'       => null,
                'subcategory_id'    => null,
                'announcement_id'   => null,
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
        ];
    }
}
