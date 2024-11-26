<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SortingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sortings')->delete();
        
        \DB::table('sortings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'newest_first',
                'alternames' => '{"cs": "Nejdříve nejnovější", "en": "Newest first", "ru": "Новые сначала"}',
                'order_number' => 1,
                'direction' => 'desc',
                'is_default' => 1,
                'is_active' => 1,
                'attribute_id' => 58,
                'created_at' => '2024-10-10 23:34:08',
                'updated_at' => '2024-11-25 17:28:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'oldest_first',
                'alternames' => '{"cs": "Nejstarší první", "en": "Oldest first", "ru": "Старые сначала"}',
                'order_number' => 2,
                'direction' => 'asc',
                'is_default' => 0,
                'is_active' => 1,
                'attribute_id' => 58,
                'created_at' => '2024-10-10 23:44:05',
                'updated_at' => '2024-11-25 17:28:06',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'cheapest_first',
                'alternames' => '{"cs": "Nejlevnější první", "en": "Cheapest first", "ru": "Дешевые сначала"}',
                'order_number' => 3,
                'direction' => 'asc',
                'is_default' => 0,
                'is_active' => 1,
                'attribute_id' => 4,
                'created_at' => '2024-10-10 23:56:45',
                'updated_at' => '2024-11-25 17:28:57',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => 'most_expensive_first',
                'alternames' => '{"cs": "Nejdražší první", "en": "Most expensive first", "ru": "Дорогие сначала"}',
                'order_number' => 4,
                'direction' => 'desc',
                'is_default' => 0,
                'is_active' => 1,
                'attribute_id' => 4,
                'created_at' => '2024-10-11 01:20:31',
                'updated_at' => '2024-11-25 17:28:58',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'slug' => 'year_of_issue',
                'alternames' => '{"cs": null, "en": "Year of issue", "ru": "Год выпуска"}',
                'order_number' => 5,
                'direction' => 'asc',
                'is_default' => 0,
                'is_active' => 1,
                'attribute_id' => 47,
                'created_at' => '2024-10-25 12:55:54',
                'updated_at' => '2024-11-25 17:28:59',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}