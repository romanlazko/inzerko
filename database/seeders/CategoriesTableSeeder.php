<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Nemovitosti", "en": "Real estate", "ru": "Недвижимость"}',
                'slug' => 'real-estate',
                'is_active' => 1,
                'created_at' => '2024-05-21 16:56:35',
                'updated_at' => '2024-08-17 15:01:17',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 1,
                'alternames' => '{"cs": "Byty", "en": "Flats", "ru": "Квартиры"}',
                'slug' => 'flats',
                'is_active' => 1,
                'created_at' => '2024-05-21 18:31:31',
                'updated_at' => '2024-08-17 15:08:21',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Práce ", "en": "Job", "ru": "Вакансии"}',
                'slug' => 'job',
                'is_active' => 1,
                'created_at' => '2024-05-22 17:30:46',
                'updated_at' => '2024-08-17 13:36:05',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Electronika", "en": "Electronic", "ru": "Электроника"}',
                'slug' => 'electronic',
                'is_active' => 1,
                'created_at' => '2024-05-23 18:32:31',
                'updated_at' => '2024-08-17 14:59:55',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 4,
                'alternames' => '{"cs": "Mobilni telefony", "en": "Phones", "ru": "Телефоны"}',
                'slug' => 'phones',
                'is_active' => 1,
                'created_at' => '2024-05-23 18:39:55',
                'updated_at' => '2024-08-17 15:18:43',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 2,
                'alternames' => '{"cs": "Dlouhodobý pronájem", "en": "Long-term rent", "ru": "Долгосрочная аренда"}',
                'slug' => 'dolgosrocnaia-arenda',
                'is_active' => 1,
                'created_at' => '2024-05-30 18:23:16',
                'updated_at' => '2024-07-16 12:18:57',
                'deleted_at' => '2024-07-16 12:18:57',
            ),
            6 => 
            array (
                'id' => 7,
                'parent_id' => 2,
                'alternames' => '{"cs": "Prodej bytu", "en": "Sale flats", "ru": "Продажа"}',
                'slug' => 'prodaza',
                'is_active' => 1,
                'created_at' => '2024-05-31 08:58:27',
                'updated_at' => '2024-07-16 12:19:05',
                'deleted_at' => '2024-07-16 12:19:05',
            ),
            7 => 
            array (
                'id' => 8,
                'parent_id' => 2,
                'alternames' => '{"cz": "Pronájem pokoje", "en": "Rent room", "ru": "Аренда комнаты"}',
                'slug' => 'arenda-komnaty',
                'is_active' => 1,
                'created_at' => '2024-07-14 10:27:05',
                'updated_at' => '2024-07-15 11:09:13',
                'deleted_at' => '2024-07-15 11:09:13',
            ),
            8 => 
            array (
                'id' => 9,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Vozidla", "en": "Vehicles", "ru": "Транспорт"}',
                'slug' => 'vehicles',
                'is_active' => 1,
                'created_at' => '2024-07-14 11:42:40',
                'updated_at' => '2024-08-17 14:58:29',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Dům a zahrada", "en": "House and garden", "ru": "Для дома и дачи"}',
                'slug' => 'house-and-garden',
                'is_active' => 1,
                'created_at' => '2024-07-14 11:47:49',
                'updated_at' => '2024-08-17 15:01:44',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Oblečení a obuv", "en": "Clothes and shoes", "ru": "Одежда и обувь"}',
                'slug' => 'clothes-and-shoes',
                'is_active' => 1,
                'created_at' => '2024-07-14 12:08:07',
                'updated_at' => '2024-08-17 15:03:25',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Služby", "en": "Services", "ru": "Услуги"}',
                'slug' => 'services',
                'is_active' => 1,
                'created_at' => '2024-07-14 12:13:47',
                'updated_at' => '2024-08-17 15:03:50',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Krása a zdraví", "en": "Beauty and health", "ru": "Красота и здоровье"}',
                'slug' => 'beauty-and-health',
                'is_active' => 1,
                'created_at' => '2024-07-14 12:16:04',
                'updated_at' => '2024-08-17 15:04:03',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Koníčky a rekreace", "en": "Hobbies and recreation", "ru": "Хобби и отдых"}',
                'slug' => 'hobbies-and-recreation',
                'is_active' => 1,
                'created_at' => '2024-07-14 12:37:14',
                'updated_at' => '2024-08-17 15:04:23',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Zvířata", "en": "Animals", "ru": "Животные"}',
                'slug' => 'animals',
                'is_active' => 1,
                'created_at' => '2024-07-14 12:58:50',
                'updated_at' => '2024-07-14 12:58:53',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Cestování", "en": "Travel", "ru": "Путешествия"}',
                'slug' => 'travel',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:03:04',
                'updated_at' => '2024-07-14 13:03:12',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'parent_id' => NULL,
                'alternames' => '{"cs": "Pro děti", "en": "For children", "ru": "Для детей"}',
                'slug' => 'for-children',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:04:41',
                'updated_at' => '2024-07-14 13:04:44',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'parent_id' => 1,
                'alternames' => '{"cs": "Dům, chata, chalupa, městský dům", "en": "House, cottage, townhouse", "ru": "Дом, дача, коттедж, таунхаус"}',
                'slug' => 'house-cottage-townhouse',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:07:57',
                'updated_at' => '2024-08-17 15:08:54',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'parent_id' => 1,
                'alternames' => '{"cs": "Pokoje", "en": "Rooms", "ru": "Комнаты"}',
                'slug' => 'komnaty',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:31:52',
                'updated_at' => '2024-07-14 13:31:54',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'parent_id' => 1,
                'alternames' => '{"cs": "Garáž a parkoviště", "en": "Garage and parking", "ru": "Гараж и парковка"}',
                'slug' => 'garage-and-parking',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:34:30',
                'updated_at' => '2024-08-17 15:09:16',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'parent_id' => 1,
                'alternames' => '{"cs": "Komerční nemovitosti", "en": "Commercial", "ru": "Коммерческая недвижимость"}',
                'slug' => 'commercial',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:37:02',
                'updated_at' => '2024-08-17 15:09:31',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'parent_id' => 1,
                'alternames' => '{"cs": "Pozemek", "en": "Land plot", "ru": "Земельный участок"}',
                'slug' => 'land-plot',
                'is_active' => 1,
                'created_at' => '2024-07-14 13:45:14',
                'updated_at' => '2024-08-17 15:09:45',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'parent_id' => 4,
                'alternames' => '{"cs": "Tablety", "en": "Tablets", "ru": "Планшеты"}',
                'slug' => 'tablets',
                'is_active' => 1,
                'created_at' => '2024-07-14 14:02:22',
                'updated_at' => '2024-08-17 15:18:53',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'parent_id' => 4,
                'alternames' => '{"cs": "Stolní počítače", "en": "Desktop computers", "ru": "Настольные компьютеры"}',
                'slug' => 'desktop-computers',
                'is_active' => 1,
                'created_at' => '2024-07-14 14:03:32',
                'updated_at' => '2024-08-17 15:19:08',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'parent_id' => 4,
                'alternames' => '{"cs": "Domácí spotřebiče", "en": "Household appliances", "ru": "Бытовая техника"}',
                'slug' => 'household-appliances',
                'is_active' => 1,
                'created_at' => '2024-07-14 14:04:11',
                'updated_at' => '2024-08-17 15:19:20',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'parent_id' => 4,
                'alternames' => '{"cs": "Notebooky", "en": "Laptops", "ru": "Ноутбуки"}',
                'slug' => 'laptops',
                'is_active' => 1,
                'created_at' => '2024-07-14 14:11:22',
                'updated_at' => '2024-08-17 15:19:30',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'parent_id' => 2,
                'alternames' => '{"cs": "Krátkodobé pronájmy", "en": "Short-term rent", "ru": "Краткосрочная аренда"}',
                'slug' => 'kratkosrocnaia-arenda',
                'is_active' => 1,
                'created_at' => '2024-07-14 14:27:44',
                'updated_at' => '2024-07-16 12:19:01',
                'deleted_at' => '2024-07-16 12:19:01',
            ),
            27 => 
            array (
                'id' => 28,
                'parent_id' => 18,
                'alternames' => '{"cs": "Dlouhodobý pronájem", "en": "Long-term rent", "ru": "Долгосрочная аренда"}',
                'slug' => 'dolgosrocnaia-arenda-1',
                'is_active' => 1,
                'created_at' => '2024-07-15 12:05:04',
                'updated_at' => '2024-07-16 11:00:17',
                'deleted_at' => '2024-07-16 11:00:17',
            ),
            28 => 
            array (
                'id' => 29,
                'parent_id' => 18,
                'alternames' => '{"cs": "Krátkodobé pronájmy", "en": "Short-term rent", "ru": "Краткосрочная аренда"}',
                'slug' => 'kratkosrocnaia-arenda-1',
                'is_active' => 1,
                'created_at' => '2024-07-15 12:05:54',
                'updated_at' => '2024-07-16 11:00:22',
                'deleted_at' => '2024-07-16 11:00:22',
            ),
            29 => 
            array (
                'id' => 30,
                'parent_id' => 18,
                'alternames' => '{"cs": "Prodej", "en": "For Sale", "ru": "Продажа"}',
                'slug' => 'prodaza-1',
                'is_active' => 1,
                'created_at' => '2024-07-15 12:06:42',
                'updated_at' => '2024-07-16 11:00:26',
                'deleted_at' => '2024-07-16 11:00:26',
            ),
            30 => 
            array (
                'id' => 31,
                'parent_id' => 10,
                'alternames' => '{"cs": "Nábytek", "en": "Furniture", "ru": "Мебель"}',
                'slug' => 'furniture',
                'is_active' => 1,
                'created_at' => '2024-07-18 11:48:25',
                'updated_at' => '2024-08-17 15:20:48',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'parent_id' => 10,
                'alternames' => '{"cs": "Domácí spotřebiče", "en": "Household appliances", "ru": "Бытовая техника"}',
                'slug' => 'bytovaia-texnika-1',
                'is_active' => 0,
                'created_at' => '2024-07-18 11:55:14',
                'updated_at' => '2024-08-17 15:20:51',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'parent_id' => 10,
                'alternames' => '{"cs": "Rostliny", "en": "Plants", "ru": "Растения"}',
                'slug' => 'plants',
                'is_active' => 1,
                'created_at' => '2024-07-18 11:57:01',
                'updated_at' => '2024-08-17 15:21:13',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'parent_id' => 10,
                'alternames' => '{"cs": "Nádobí a kuchyňské potřeby", "en": "Cookware and kitchen", "ru": "Посуда и кухня"}',
                'slug' => 'cookware-and-kitchen',
                'is_active' => 1,
                'created_at' => '2024-07-18 11:59:10',
                'updated_at' => '2024-08-17 15:21:30',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'parent_id' => 10,
                'alternames' => '{"cs": "Interiér", "en": "Interior", "ru": "Интерьер"}',
                'slug' => 'interior',
                'is_active' => 1,
                'created_at' => '2024-07-18 12:03:06',
                'updated_at' => '2024-08-17 15:21:45',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'parent_id' => 10,
                'alternames' => '{"cs": "Dětský nábytek", "en": "Children\'s furniture", "ru": "Детская мебель"}',
                'slug' => 'detskaia-mebel',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:06:32',
                'updated_at' => '2024-07-19 09:06:39',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'parent_id' => 31,
                'alternames' => '{"cs": "Postele", "en": "Beds", "ru": "Кровати"}',
                'slug' => 'beds',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:08:39',
                'updated_at' => '2024-08-17 15:29:48',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'parent_id' => 31,
                'alternames' => '{"cs": "Pohovky", "en": "Sofas", "ru": "Диваны"}',
                'slug' => 'sofas',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:09:34',
                'updated_at' => '2024-08-17 15:30:10',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'parent_id' => 31,
                'alternames' => '{"cs": "Křesla", "en": "Armchairs", "ru": "Кресла"}',
                'slug' => 'armchairs',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:10:22',
                'updated_at' => '2024-08-17 15:32:04',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'parent_id' => 31,
                'alternames' => '{"cs": "Skříně", "en": "Сlosets", "ru": "Шкафы"}',
                'slug' => 'slosets',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:13:22',
                'updated_at' => '2024-08-17 15:30:38',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'parent_id' => 31,
                'alternames' => '{"cs": "Stoly", "en": "Tables", "ru": "Столы"}',
                'slug' => 'tables',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:31:58',
                'updated_at' => '2024-08-17 15:30:51',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'parent_id' => 31,
                'alternames' => '{"cs": "Komody", "en": "Commodes", "ru": "Комоды"}',
                'slug' => 'commodes',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:34:10',
                'updated_at' => '2024-08-17 15:31:03',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'parent_id' => 31,
                'alternames' => '{"cs": "Židle", "en": "Chairs", "ru": "Стулья"}',
                'slug' => 'chairs',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:35:50',
                'updated_at' => '2024-08-17 15:32:12',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'parent_id' => 31,
                'alternames' => '{"cs": "Regály a regálové jednotky", "en": "Racks and shelving units", "ru": "Стеллажи и этажерки"}',
                'slug' => 'racks-and-shelving-units',
                'is_active' => 1,
                'created_at' => '2024-07-19 09:38:57',
                'updated_at' => '2024-08-17 15:32:26',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'parent_id' => 9,
                'alternames' => '{"cs": "Nákladní automobily", "en": "Trucks", "ru": "Грузовые"}',
                'slug' => 'trucks',
                'is_active' => 1,
                'created_at' => '2024-08-17 15:36:19',
                'updated_at' => '2024-08-17 15:36:21',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'parent_id' => 9,
                'alternames' => '{"cs": "Osobní automobily", "en": "Passenger cars", "ru": "Легковые автомобили"}',
                'slug' => 'passenger-cars',
                'is_active' => 1,
                'created_at' => '2024-08-17 15:39:37',
                'updated_at' => '2024-08-17 17:48:31',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'parent_id' => 9,
                'alternames' => '{"cs": "Motocykly", "en": "Motorcycles", "ru": "Мотоциклы"}',
                'slug' => 'motorcycles',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:40:36',
                'updated_at' => '2024-08-17 15:40:36',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'parent_id' => 9,
                'alternames' => '{"cs": "Čtyřkolky", "en": "ATVs", "ru": "Квадроциклы"}',
                'slug' => 'atvs',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:41:16',
                'updated_at' => '2024-08-17 15:41:16',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'parent_id' => 9,
                'alternames' => '{"cs": "Přívěsy", "en": "Trailers", "ru": "Прицепы"}',
                'slug' => 'trailers',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:41:50',
                'updated_at' => '2024-08-17 15:41:50',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'parent_id' => 9,
                'alternames' => '{"cs": "Autobusy", "en": "Buses", "ru": "Автобусы"}',
                'slug' => 'buses',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:42:25',
                'updated_at' => '2024-08-17 15:42:25',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'parent_id' => 9,
                'alternames' => '{"cs": "Obytná vozidla", "en": "Residential vehicles", "ru": "Жилые"}',
                'slug' => 'residential-vehicles',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:43:33',
                'updated_at' => '2024-08-17 15:43:33',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'parent_id' => 9,
                'alternames' => '{"cs": "Užitková auta", "en": "Commercial vehicles", "ru": "Коммерческий транспорт"}',
                'slug' => 'commercial-vehicles',
                'is_active' => 0,
                'created_at' => '2024-08-17 15:45:38',
                'updated_at' => '2024-08-17 15:45:38',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}