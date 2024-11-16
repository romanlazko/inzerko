<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pages')->delete();
        
        \DB::table('pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'podminky-vyuzivani-sluzeb-serveru-inzerkocz',
                'title' => 'Podmínky využívání služeb serveru inzerko.cz',
                'alternames' => '{"cs": "Podminky", "en": "Privacy", "ru": "Условия"}',
                'is_active' => 1,
                'is_header' => 0,
                'is_footer' => 1,
                'created_at' => '2024-11-03 18:24:51',
                'updated_at' => '2024-11-16 14:09:06',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'podminky-reklamy-pro-web-inzerkocz',
                'title' => 'Podmínky reklamy pro web inzerko.cz',
                'alternames' => '{"en": "Advertisement", "ru": "Реклама"}',
                'is_active' => 1,
                'is_header' => 0,
                'is_footer' => 1,
                'created_at' => '2024-11-03 18:39:22',
                'updated_at' => '2024-11-16 14:09:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}