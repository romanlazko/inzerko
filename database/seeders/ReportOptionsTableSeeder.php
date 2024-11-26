<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReportOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('report_options')->delete();
        
        \DB::table('report_options')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'fraud',
                'alternames' => '{"cs": "Podvody", "en": "Fraud", "ru": "Мошенничество"}',
                'is_active' => 1,
                'order_number' => 1,
                'created_at' => '2024-11-25 16:57:54',
                'updated_at' => '2024-11-25 16:58:02',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'i_think_it\'s_a_fake',
                'alternames' => '{"cs": "Myslím, že je to padělek", "en": "I think it\'s a fake", "ru": "Мне кажется это подделка"}',
                'is_active' => 1,
                'order_number' => 2,
                'created_at' => '2024-11-25 16:58:43',
                'updated_at' => '2024-11-25 16:58:44',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'inappropriate_or_rude_communication',
                'alternames' => '{"cs": "Nevhodná nebo hrubá komunikace", "en": "Inappropriate or rude communication", "ru": "Неуместное или грубое общение"}',
                'is_active' => 1,
                'order_number' => 3,
                'created_at' => '2024-11-25 16:59:33',
                'updated_at' => '2024-11-25 16:59:34',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}