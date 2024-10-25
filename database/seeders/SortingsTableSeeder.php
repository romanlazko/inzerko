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
                'attribute_id' => 58,
                'is_default' => 1,
                'created_at' => '2024-10-10 23:34:08',
                'updated_at' => '2024-10-16 10:48:29',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'oldest_first',
                'alternames' => '{"cs": "Nejstarší první", "en": "Oldest first", "ru": "Старые сначала"}',
                'order_number' => 2,
                'direction' => 'asc',
                'attribute_id' => 58,
                'is_default' => 0,
                'created_at' => '2024-10-10 23:44:05',
                'updated_at' => '2024-10-16 10:48:23',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'cheapest_first',
                'alternames' => '{"cs": "Nejlevnější první", "en": "Cheapest first", "ru": "Дешевые сначала"}',
                'order_number' => 3,
                'direction' => 'asc',
                'attribute_id' => 4,
                'is_default' => 0,
                'created_at' => '2024-10-10 23:56:45',
                'updated_at' => '2024-10-11 03:05:19',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => 'most_expensive_first',
                'alternames' => '{"cs": "Nejdražší první", "en": "Most expensive first", "ru": "Дорогие сначала"}',
                'order_number' => 4,
                'direction' => 'desc',
                'attribute_id' => 4,
                'is_default' => 0,
                'created_at' => '2024-10-11 01:20:31',
                'updated_at' => '2024-10-11 01:20:31',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}