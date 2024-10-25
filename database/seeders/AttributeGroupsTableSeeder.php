<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attribute_groups')->delete();
        
        \DB::table('attribute_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'price',
                'separator' => NULL,
                'created_at' => '2024-10-07 21:50:41',
                'updated_at' => '2024-10-07 21:50:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'title',
                'separator' => ',',
                'created_at' => '2024-10-07 21:53:41',
                'updated_at' => '2024-10-07 21:53:41',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'description',
                'separator' => NULL,
                'created_at' => '2024-10-07 22:32:47',
                'updated_at' => '2024-10-07 22:32:47',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}