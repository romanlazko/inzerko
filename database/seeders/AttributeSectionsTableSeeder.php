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
                'slug' => 'basic_information',
                'alternames' => '{"cs": "Základní informace", "en": "Basic information", "ru": "Основная информация"}',
                'type' => 'create',
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => '2024-05-21 16:49:07',
                'updated_at' => '2024-11-26 21:42:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => 'prices',
                'alternames' => '{"cs": "Ceniky", "en": "Prices", "ru": "Цены"}',
                'type' => 'filter',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-05-21 17:37:18',
                'updated_at' => '2024-11-26 21:30:43',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => 'real_estate_type',
                'alternames' => '{"cs": "Typ", "en": "Type", "ru": "Тип"}',
                'type' => NULL,
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => '2024-05-21 17:49:24',
                'updated_at' => '2024-11-26 22:57:35',
                'deleted_at' => '2024-11-26 22:57:35',
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => 'building_information',
                'alternames' => '{"cs": "Informace o budově", "en": "Building information", "ru": "Информация о здании"}',
                'type' => 'create',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-05-21 18:41:49',
                'updated_at' => '2024-11-26 14:33:41',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'slug' => 'property_information',
                'alternames' => '{"cs": "Informace o objektu", "en": "Property information", "ru": "Информация об объекте"}',
                'type' => 'show',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-05-22 11:09:33',
                'updated_at' => '2024-11-26 14:15:45',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'slug' => 'more_information',
                'alternames' => '{"cs": "Doplňující informace", "en": "More information", "ru": "Дополнительная информация "}',
                'type' => 'show',
                'order_number' => 10,
                'is_active' => 1,
                'created_at' => '2024-05-23 18:48:01',
                'updated_at' => '2024-11-26 14:16:07',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'slug' => 'remuneration',
                'alternames' => '{"cs": "Odměna za práci", "en": "Remuneration", "ru": "Оплата труда"}',
                'type' => NULL,
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-05-23 19:48:37',
                'updated_at' => '2024-11-26 22:57:52',
                'deleted_at' => '2024-11-26 22:57:52',
            ),
            7 => 
            array (
                'id' => 8,
                'slug' => 'memory',
                'alternames' => '{"cs": "Paměť", "en": "Memory", "ru": "Память"}',
                'type' => 'filter',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-05-25 14:58:25',
                'updated_at' => '2024-11-26 21:26:14',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'slug' => 'display',
                'alternames' => '{"cs": "Displej", "en": "Display", "ru": "Дисплей"}',
                'type' => 'filter',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-05-25 15:15:12',
                'updated_at' => '2024-11-26 21:25:30',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'slug' => 'location',
                'alternames' => '{"cs": "Lokace", "en": "Location", "ru": "Расположение"}',
                'type' => 'filter',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-05-28 09:01:11',
                'updated_at' => '2024-11-26 14:40:48',
                'deleted_at' => '2024-11-26 14:40:48',
            ),
            10 => 
            array (
                'id' => 11,
                'slug' => 'title',
                'alternames' => '{"cs": "Jmeno", "en": "Title", "ru": "Заголовок"}',
                'type' => NULL,
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => '2024-08-09 18:51:29',
                'updated_at' => '2024-11-26 22:57:40',
                'deleted_at' => '2024-11-26 22:57:40',
            ),
            11 => 
            array (
                'id' => 12,
                'slug' => 'price',
                'alternames' => '{"cs": "Cena", "en": "Price", "ru": "Цена"}',
                'type' => NULL,
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => '2024-08-09 21:02:22',
                'updated_at' => '2024-11-26 22:57:44',
                'deleted_at' => '2024-11-26 22:57:44',
            ),
            12 => 
            array (
                'id' => 13,
                'slug' => 'type',
                'alternames' => '{"cs": "Typ", "en": "Type", "ru": "Тип"}',
                'type' => NULL,
                'order_number' => 1,
                'is_active' => 1,
                'created_at' => '2024-08-10 17:23:17',
                'updated_at' => '2024-11-26 22:57:48',
                'deleted_at' => '2024-11-26 22:57:48',
            ),
            13 => 
            array (
                'id' => 14,
                'slug' => 'vehicle_condition',
                'alternames' => '{"cs": "Stav vozidla", "en": "Vehicle condition", "ru": "Состояние автомобиля"}',
                'type' => 'filter',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-08-17 15:53:35',
                'updated_at' => '2024-11-26 22:49:48',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'slug' => 'vehicle_type_and_model',
                'alternames' => '{"cs": "Typ a model vozidla", "en": "Vehicle type and model", "ru": "Тип и модель автомобиля"}',
                'type' => 'filter',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-08-17 16:10:34',
                'updated_at' => '2024-11-26 22:50:04',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'slug' => 'engine',
                'alternames' => '{"cs": "Motor", "en": "Engine", "ru": "Мотор"}',
                'type' => 'filter',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-08-18 11:18:38',
                'updated_at' => '2024-11-26 21:18:16',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'slug' => 'transmission',
                'alternames' => '{"cs": "Transmise", "en": "Transmission", "ru": "Трансмиссия"}',
                'type' => 'filter',
                'order_number' => 7,
                'is_active' => 1,
                'created_at' => '2024-08-19 09:19:57',
                'updated_at' => '2024-11-26 22:49:12',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'slug' => 'hidden',
                'alternames' => '{"cs": null, "en": "Hidden", "ru": "Скрытая"}',
                'type' => NULL,
                'order_number' => 0,
                'is_active' => 0,
                'created_at' => '2024-10-10 19:39:19',
                'updated_at' => '2024-11-26 14:36:57',
                'deleted_at' => '2024-11-26 14:36:57',
            ),
            18 => 
            array (
                'id' => 19,
                'slug' => 'requirements_for_the_candidate',
                'alternames' => '{"cs": "Požadavky na uchazeče", "en": "Requirements for the candidate", "ru": "Требования к кандидату"}',
                'type' => 'show',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-25 18:27:18',
                'updated_at' => '2024-11-26 15:19:30',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'slug' => 'employment',
                'alternames' => '{"cs": "Zaměstnání", "en": "Employment", "ru": "Занятость"}',
                'type' => NULL,
                'order_number' => 1,
                'is_active' => 0,
                'created_at' => '2024-11-25 20:44:49',
                'updated_at' => '2024-11-26 14:36:24',
                'deleted_at' => '2024-11-26 14:36:24',
            ),
            20 => 
            array (
                'id' => 21,
                'slug' => 'working_conditions',
                'alternames' => '{"cs": "Pracovní podmínky", "en": "Working conditions", "ru": "Условия работы"}',
                'type' => 'create',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-11-25 21:30:31',
                'updated_at' => '2024-12-08 13:23:10',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'slug' => 'description',
                'alternames' => '{"cs": "Popis", "en": "Description", "ru": "Описание"}',
                'type' => 'show',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 13:08:38',
                'updated_at' => '2024-11-26 15:19:31',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'slug' => 'hidden',
                'alternames' => '{"en": "Hidden"}',
                'type' => 'show',
                'order_number' => 0,
                'is_active' => 0,
                'created_at' => '2024-11-26 14:24:44',
                'updated_at' => '2024-11-26 15:19:41',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'slug' => 'hidden',
                'alternames' => '{"en": "Hidden"}',
                'type' => 'create',
                'order_number' => 0,
                'is_active' => 0,
                'created_at' => '2024-11-26 14:27:21',
                'updated_at' => '2024-11-26 14:27:21',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'slug' => 'hidden',
                'alternames' => '{"en": "Hidden"}',
                'type' => 'filter',
                'order_number' => 0,
                'is_active' => 0,
                'created_at' => '2024-11-26 14:28:00',
                'updated_at' => '2024-11-26 14:28:00',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'slug' => 'location',
                'alternames' => '{"cs": "Umístění", "en": "Location", "ru": "Расположение"}',
                'type' => 'create',
                'order_number' => 3,
                'is_active' => 0,
                'created_at' => '2024-11-26 14:29:39',
                'updated_at' => '2024-11-26 14:39:49',
                'deleted_at' => '2024-11-26 14:39:49',
            ),
            26 => 
            array (
                'id' => 27,
                'slug' => 'building_information',
                'alternames' => '{"cs": "Informace o budově", "en": "Building information", "ru": "Информация о здании"}',
                'type' => 'filter',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:33:19',
                'updated_at' => '2024-11-26 14:34:01',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'slug' => 'building_information',
                'alternames' => '{"cs": "Informace o budově", "en": "Building information", "ru": "Информация о здании"}',
                'type' => 'show',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:33:44',
                'updated_at' => '2024-11-26 14:35:56',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'slug' => 'description',
                'alternames' => '{"cs": "Popis", "en": "Description", "ru": "Описание"}',
                'type' => 'create',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:36:08',
                'updated_at' => '2024-11-26 15:24:45',
                'deleted_at' => '2024-11-26 15:24:45',
            ),
            29 => 
            array (
                'id' => 30,
                'slug' => 'description',
                'alternames' => '{"cs": "Popis", "en": "Description", "ru": "Описание"}',
                'type' => 'filter',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:36:11',
                'updated_at' => '2024-11-26 15:19:27',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'slug' => 'location',
                'alternames' => '{"cs": "Umístění", "en": "Location", "ru": "Расположение"}',
                'type' => 'filter',
                'order_number' => 3,
                'is_active' => 0,
                'created_at' => '2024-11-26 14:37:03',
                'updated_at' => '2024-11-26 14:39:54',
                'deleted_at' => '2024-11-26 14:39:54',
            ),
            31 => 
            array (
                'id' => 32,
                'slug' => 'property_information',
                'alternames' => '{"cs": "Informace o objektu", "en": "Property information", "ru": "Информация об объекте"}',
                'type' => 'create',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:37:25',
                'updated_at' => '2024-12-03 12:25:22',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'slug' => 'property_information',
                'alternames' => '{"cs": "Informace o objektu", "en": "Property information", "ru": "Информация об объекте"}',
                'type' => 'filter',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:37:31',
                'updated_at' => '2024-11-26 14:37:40',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'slug' => 'working_conditions',
                'alternames' => '{"cs": "Pracovní podmínky", "en": "Working conditions", "ru": "Условия работы"}',
                'type' => 'show',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:38:06',
                'updated_at' => '2024-12-08 13:23:46',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'slug' => 'working_conditions',
                'alternames' => '{"cs": "Pracovní podmínky", "en": "Working conditions", "ru": "Условия работы"}',
                'type' => 'filter',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:38:15',
                'updated_at' => '2024-12-08 13:23:30',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'slug' => 'location',
                'alternames' => '{"cs": "Lokace", "en": "Location", "ru": "Расположение"}',
                'type' => 'create',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:40:05',
                'updated_at' => '2024-12-03 12:25:13',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'slug' => 'location',
                'alternames' => '{"cs": "Lokace", "en": "Location", "ru": "Расположение"}',
                'type' => 'show',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:40:08',
                'updated_at' => '2024-11-26 14:40:36',
                'deleted_at' => '2024-11-26 14:40:36',
            ),
            37 => 
            array (
                'id' => 38,
                'slug' => 'requirements_for_the_candidate',
                'alternames' => '{"cs": "Požadavky na uchazeče", "en": "Requirements for the candidate", "ru": "Требования к кандидату"}',
                'type' => 'create',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:47:09',
                'updated_at' => '2024-12-03 12:25:28',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'slug' => 'requirements_for_the_candidate',
                'alternames' => '{"cs": "Požadavky na uchazeče", "en": "Requirements for the candidate", "ru": "Требования к кандидату"}',
                'type' => 'filter',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 14:47:12',
                'updated_at' => '2024-11-26 15:19:26',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'slug' => 'location',
                'alternames' => '{"cs": "Lokace", "en": "Location", "ru": "Расположение"}',
                'type' => 'show',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-11-26 15:02:51',
                'updated_at' => '2024-11-26 15:11:29',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'slug' => 'basic_information',
                'alternames' => '{"cs": "Základní informace", "en": "Basic information", "ru": "Основная информация"}',
                'type' => 'filter',
                'order_number' => 2,
                'is_active' => 1,
                'created_at' => '2024-11-26 15:05:32',
                'updated_at' => '2024-11-26 15:05:35',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'slug' => 'basic_information',
                'alternames' => '{"cs": "Základní informace", "en": "Basic information", "ru": "Основная информация"}',
                'type' => 'show',
                'order_number' => 2,
                'is_active' => 1,
                'created_at' => '2024-11-26 15:06:42',
                'updated_at' => '2024-11-26 15:06:49',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'slug' => 'engine',
                'alternames' => '{"cs": "Motor", "en": "Engine", "ru": "Мотор"}',
                'type' => 'create',
                'order_number' => 7,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:18:12',
                'updated_at' => '2024-12-03 12:25:46',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'slug' => 'engine',
                'alternames' => '{"cs": "Motor", "en": "Engine", "ru": "Мотор"}',
                'type' => 'show',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:18:28',
                'updated_at' => '2024-11-26 21:18:32',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'slug' => 'display',
                'alternames' => '{"cs": "Displej", "en": "Display", "ru": "Дисплей"}',
                'type' => 'create',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:22:16',
                'updated_at' => '2024-11-26 21:25:18',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'slug' => 'display',
                'alternames' => '{"cs": "Displej", "en": "Display", "ru": "Дисплей"}',
                'type' => 'show',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:22:19',
                'updated_at' => '2024-11-26 21:25:43',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'slug' => 'memory',
                'alternames' => '{"cs": "Paměť", "en": "Memory", "ru": "Память"}',
                'type' => 'create',
                'order_number' => 5,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:26:07',
                'updated_at' => '2024-12-03 12:25:31',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'slug' => 'memory',
                'alternames' => '{"cs": "Paměť", "en": "Memory", "ru": "Память"}',
                'type' => 'show',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:26:11',
                'updated_at' => '2024-11-26 21:26:21',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'slug' => 'prices',
                'alternames' => '{"cs": "Ceniky", "en": "Prices", "ru": "Цены"}',
                'type' => 'create',
                'order_number' => 2,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:30:35',
                'updated_at' => '2024-11-26 21:42:24',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'slug' => 'prices',
                'alternames' => '{"cs": "Ceniky", "en": "Prices", "ru": "Цены"}',
                'type' => 'show',
                'order_number' => 3,
                'is_active' => 1,
                'created_at' => '2024-11-26 21:30:38',
                'updated_at' => '2024-11-26 21:30:49',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'slug' => 'transmission',
                'alternames' => '{"cs": "Transmise", "en": "Transmission", "ru": "Трансмиссия"}',
                'type' => 'create',
                'order_number' => 9,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:49:06',
                'updated_at' => '2024-12-03 12:26:02',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'slug' => 'transmission',
                'alternames' => '{"cs": "Transmise", "en": "Transmission", "ru": "Трансмиссия"}',
                'type' => 'show',
                'order_number' => 7,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:49:09',
                'updated_at' => '2024-11-26 22:49:18',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'slug' => 'vehicle_condition',
                'alternames' => '{"cs": "Stav vozidla", "en": "Vehicle condition", "ru": "Состояние автомобиля"}',
                'type' => 'create',
                'order_number' => 8,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:49:34',
                'updated_at' => '2024-12-03 12:25:53',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'slug' => 'vehicle_condition',
                'alternames' => '{"cs": "Stav vozidla", "en": "Vehicle condition", "ru": "Состояние автомобиля"}',
                'type' => 'show',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:49:39',
                'updated_at' => '2024-11-26 22:49:53',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'slug' => 'vehicle_type_and_model',
                'alternames' => '{"cs": "Typ a model vozidla", "en": "Vehicle type and model", "ru": "Тип и модель автомобиля"}',
                'type' => 'create',
                'order_number' => 6,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:49:58',
                'updated_at' => '2024-12-03 12:25:42',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'slug' => 'vehicle_type_and_model',
                'alternames' => '{"cs": "Typ a model vozidla", "en": "Vehicle type and model", "ru": "Тип и модель автомобиля"}',
                'type' => 'show',
                'order_number' => 4,
                'is_active' => 1,
                'created_at' => '2024-11-26 22:50:01',
                'updated_at' => '2024-11-26 22:50:09',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'slug' => 'the_company_provides',
                'alternames' => '{"cs": "Společnost poskytuje", "en": "The company provides", "ru": "Компания предоставляет"}',
                'type' => 'create',
                'order_number' => 8,
                'is_active' => 1,
                'created_at' => '2024-12-01 21:17:24',
                'updated_at' => '2024-12-01 21:28:04',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'slug' => 'the_company_provides',
                'alternames' => '{"cs": "Společnost poskytuje", "en": "The company provides", "ru": "Компания предоставляет"}',
                'type' => 'filter',
                'order_number' => 8,
                'is_active' => 1,
                'created_at' => '2024-12-01 21:18:09',
                'updated_at' => '2024-12-01 21:28:49',
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'slug' => 'the_company_provides',
                'alternames' => '{"cs": "Společnost poskytuje", "en": "The company provides", "ru": "Компания предоставляет"}',
                'type' => 'show',
                'order_number' => 8,
                'is_active' => 1,
                'created_at' => '2024-12-01 21:18:24',
                'updated_at' => '2024-12-01 21:29:19',
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'slug' => 'remuneration',
                'alternames' => '{"cs": "Odměňování", "en": "Remuneration", "ru": "Оплата труда"}',
                'type' => 'create',
                'order_number' => 2,
                'is_active' => 1,
                'created_at' => '2024-12-03 12:21:18',
                'updated_at' => '2024-12-03 12:27:15',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}