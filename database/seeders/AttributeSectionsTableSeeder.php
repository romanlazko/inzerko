<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeSectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attribute_sections')->delete();
        
        \DB::table('attribute_sections')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'required_information',
                'alternames' => '{"cz": "Povinné informace", "en": "Required information", "ru": "Обязательная информация"}',
                'order_number' => 2,
                'is_visible' => 1,
                'created_at' => '2024-05-21 16:49:07',
                'updated_at' => '2024-10-10 19:25:27',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'prices',
                'alternames' => '{"cz": "Ceniky", "en": "Prices", "ru": "Цены"}',
                'order_number' => 3,
                'is_visible' => 1,
                'created_at' => '2024-05-21 17:37:18',
                'updated_at' => '2024-10-10 19:25:26',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'real_estate_type',
                'alternames' => '{"cz": "Typ", "en": "Type", "ru": "Тип"}',
                'order_number' => 1,
                'is_visible' => 1,
                'created_at' => '2024-05-21 17:49:24',
                'updated_at' => '2024-10-10 19:25:26',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => 'building_information',
                'alternames' => '{"cz": "Informace o budově", "en": "Building information", "ru": "Информация о здании"}',
                'order_number' => 5,
                'is_visible' => 1,
                'created_at' => '2024-05-21 18:41:49',
                'updated_at' => '2024-10-10 19:25:25',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'slug' => 'property_information',
                'alternames' => '{"cz": "Informace o objektu", "en": "Property information", "ru": "Информация об объекте"}',
                'order_number' => 4,
                'is_visible' => 1,
                'created_at' => '2024-05-22 11:09:33',
                'updated_at' => '2024-05-22 11:23:34',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'slug' => 'more_information',
                'alternames' => '{"cz": "Doplňující informace", "en": "More information", "ru": "Дополнительная информация "}',
                'order_number' => 3,
                'is_visible' => 1,
                'created_at' => '2024-05-23 18:48:01',
                'updated_at' => '2024-05-23 18:48:01',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'slug' => 'remuneration',
                'alternames' => '{"cz": "Odměna za práci", "en": "Remuneration", "ru": "Оплата труда"}',
                'order_number' => 3,
                'is_visible' => 1,
                'created_at' => '2024-05-23 19:48:37',
                'updated_at' => '2024-05-24 09:52:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'slug' => 'phone_memory',
                'alternames' => '{"cz": "Paměť", "en": "Memory", "ru": "Память"}',
                'order_number' => 4,
                'is_visible' => 1,
                'created_at' => '2024-05-25 14:58:25',
                'updated_at' => '2024-05-25 14:58:25',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'slug' => 'phone_display',
                'alternames' => '{"cz": "Displej", "en": "Display", "ru": "Дисплей"}',
                'order_number' => 5,
                'is_visible' => 1,
                'created_at' => '2024-05-25 15:15:12',
                'updated_at' => '2024-05-25 15:15:12',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'slug' => 'location',
                'alternames' => '{"cz": "Lokace", "en": "Location", "ru": "Расположение"}',
                'order_number' => 3,
                'is_visible' => 1,
                'created_at' => '2024-05-28 09:01:11',
                'updated_at' => '2024-08-11 15:28:58',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'slug' => 'title',
                'alternames' => '{"cs": "Jmeno", "en": "Title", "ru": "Заголовок"}',
                'order_number' => 1,
                'is_visible' => 1,
                'created_at' => '2024-08-09 18:51:29',
                'updated_at' => '2024-08-09 18:51:29',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'slug' => 'price',
                'alternames' => '{"cs": "Cena", "en": "Price", "ru": "Цена"}',
                'order_number' => 1,
                'is_visible' => 1,
                'created_at' => '2024-08-09 21:02:22',
                'updated_at' => '2024-08-09 21:02:22',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'slug' => 'type',
                'alternames' => '{"cs": "Typ", "en": "Type", "ru": "Тип"}',
                'order_number' => 1,
                'is_visible' => 1,
                'created_at' => '2024-08-10 17:23:17',
                'updated_at' => '2024-08-10 17:23:17',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'slug' => 'vehicle_condition',
                'alternames' => '{"cs": "Stav vozidla", "en": "Vehicle condition", "ru": "Состояние автомобиля"}',
                'order_number' => 6,
                'is_visible' => 1,
                'created_at' => '2024-08-17 15:53:35',
                'updated_at' => '2024-08-17 16:33:46',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'slug' => 'vehicle_type_and_model',
                'alternames' => '{"cs": "Typ a model vozidla", "en": "Vehicle type and model", "ru": "Тип и модель автомобиля"}',
                'order_number' => 4,
                'is_visible' => 1,
                'created_at' => '2024-08-17 16:10:34',
                'updated_at' => '2024-08-17 16:10:34',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'slug' => 'engine',
                'alternames' => '{"cs": "Motor", "en": "Engine", "ru": "Мотор"}',
                'order_number' => 6,
                'is_visible' => 1,
                'created_at' => '2024-08-18 11:18:38',
                'updated_at' => '2024-08-18 11:18:38',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'slug' => 'transmission',
                'alternames' => '{"cs": "Transmise", "en": "Transmission", "ru": "Трансмиссия"}',
                'order_number' => 7,
                'is_visible' => 1,
                'created_at' => '2024-08-19 09:19:57',
                'updated_at' => '2024-08-19 09:40:49',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'slug' => 'hidden',
                'alternames' => '{"cs": null, "en": "Hidden", "ru": "Скрытая"}',
                'order_number' => 0,
                'is_visible' => 0,
                'created_at' => '2024-10-10 19:39:19',
                'updated_at' => '2024-10-10 19:51:01',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}