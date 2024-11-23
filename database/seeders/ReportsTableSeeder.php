<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('reports')->delete();
        
        \DB::table('reports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'reportable_type' => 'App\\Models\\Announcement',
                'reportable_id' => 3,
                'reporter_id' => 1,
                'report_option_id' => 1,
                'description' => 'Кинул меня',
                'is_active' => NULL,
                'created_at' => '2024-11-23 15:30:08',
                'updated_at' => '2024-11-23 15:30:08',
            ),
            1 => 
            array (
                'id' => 2,
                'reportable_type' => 'App\\Models\\Announcement',
                'reportable_id' => 3,
                'reporter_id' => 1,
                'report_option_id' => 1,
                'description' => 'Мошенник',
                'is_active' => NULL,
                'created_at' => '2024-11-23 17:55:38',
                'updated_at' => '2024-11-23 17:55:38',
            ),
        ));
        
        
    }
}