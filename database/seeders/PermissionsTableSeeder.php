<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'default',
                'guard_name' => 'web',
                'comment' => 'Default permission',
                'created_at' => '2024-11-07 11:52:12',
                'updated_at' => '2024-11-07 11:52:12',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'moderate',
                'guard_name' => 'announcement',
                'comment' => 'Модерировать объявления',
                'created_at' => '2024-11-17 20:49:57',
                'updated_at' => '2024-11-18 16:13:43',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'view',
                'guard_name' => 'system_log',
                'comment' => 'Видеть системные логи',
                'created_at' => '2024-11-18 15:46:00',
                'updated_at' => '2024-11-18 16:14:00',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'view',
                'guard_name' => 'announcement',
                'comment' => 'Видеть объявления',
                'created_at' => '2024-11-18 15:51:01',
                'updated_at' => '2024-11-18 16:13:48',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'delete',
                'guard_name' => 'announcement',
                'comment' => 'Удалять объявления',
                'created_at' => '2024-11-18 16:07:00',
                'updated_at' => '2024-11-18 16:13:54',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'view',
                'guard_name' => 'telegram',
                'comment' => 'Видеть телеграм',
                'created_at' => '2024-11-18 16:11:58',
                'updated_at' => '2024-11-18 16:11:58',
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'view',
                'guard_name' => 'user',
                'comment' => 'Видеть пользователей',
                'created_at' => '2024-11-18 16:13:34',
                'updated_at' => '2024-11-18 16:13:34',
            ),
            7 => 
            array (
                'id' => 9,
                'name' => 'view',
                'guard_name' => 'page',
                'comment' => 'Видеть страницы',
                'created_at' => '2024-11-18 16:15:36',
                'updated_at' => '2024-11-18 16:15:56',
            ),
            8 => 
            array (
                'id' => 10,
                'name' => 'view',
                'guard_name' => 'setting',
                'comment' => 'Видеть настройки',
                'created_at' => '2024-11-18 16:16:26',
                'updated_at' => '2024-11-18 16:16:26',
            ),
            9 => 
            array (
                'id' => 11,
                'name' => 'manage',
                'guard_name' => 'announcement',
                'comment' => 'Управлять объявлениями: create, view, update, delete, moderate, force_delete',
                'created_at' => '2024-11-18 16:22:51',
                'updated_at' => '2024-11-21 14:30:09',
            ),
            10 => 
            array (
                'id' => 12,
                'name' => 'manage',
                'guard_name' => 'role',
                'comment' => 'Управлять ролями: create, view, update, delete',
                'created_at' => '2024-11-18 17:27:03',
                'updated_at' => '2024-11-18 22:22:23',
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'manage',
                'guard_name' => 'permission',
                'comment' => 'Управлять разрешениями: create, view, update, delete',
                'created_at' => '2024-11-18 17:27:35',
                'updated_at' => '2024-11-18 17:48:31',
            ),
            12 => 
            array (
                'id' => 14,
                'name' => 'delete',
                'guard_name' => 'user',
                'comment' => 'Удалять пользователя',
                'created_at' => '2024-11-18 17:33:37',
                'updated_at' => '2024-11-18 17:33:37',
            ),
            13 => 
            array (
                'id' => 15,
                'name' => 'manage',
                'guard_name' => 'user',
                'comment' => 'Управлять пользователем: create, view, update, delete',
                'created_at' => '2024-11-18 17:34:04',
                'updated_at' => '2024-11-18 17:48:40',
            ),
            14 => 
            array (
                'id' => 16,
                'name' => 'manage',
                'guard_name' => 'page',
                'comment' => 'Управлять страницами: create, view, update, delete',
                'created_at' => '2024-11-18 17:35:19',
                'updated_at' => '2024-11-18 17:48:20',
            ),
            15 => 
            array (
                'id' => 17,
                'name' => 'view',
                'guard_name' => 'category',
                'comment' => 'Видеть категории',
                'created_at' => '2024-11-18 18:18:40',
                'updated_at' => '2024-11-18 18:18:40',
            ),
            16 => 
            array (
                'id' => 18,
                'name' => 'manage',
                'guard_name' => 'category',
                'comment' => 'Управлять категориями: create, view, update, delete, moderate',
                'created_at' => '2024-11-18 18:19:04',
                'updated_at' => '2024-11-18 18:21:55',
            ),
            17 => 
            array (
                'id' => 19,
                'name' => 'update',
                'guard_name' => 'user',
                'comment' => 'Обновлять пользователя',
                'created_at' => '2024-11-18 18:23:28',
                'updated_at' => '2024-11-18 18:23:28',
            ),
            18 => 
            array (
                'id' => 20,
                'name' => 'delete',
                'guard_name' => 'telegram',
                'comment' => 'Удалять ботов',
                'created_at' => '2024-11-18 22:20:10',
                'updated_at' => '2024-11-18 22:20:10',
            ),
            19 => 
            array (
                'id' => 21,
                'name' => 'create',
                'guard_name' => 'telegram',
                'comment' => 'Создавать ботов',
                'created_at' => '2024-11-18 22:20:52',
                'updated_at' => '2024-11-18 22:20:52',
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'manage',
                'guard_name' => 'telegram',
                'comment' => 'Управлять ботами: create, view, update, delete',
                'created_at' => '2024-11-18 22:21:14',
                'updated_at' => '2024-11-18 22:22:13',
            ),
            21 => 
            array (
                'id' => 23,
                'name' => 'force_delete',
                'guard_name' => 'announcement',
                'comment' => 'Полностью удалять объявления',
                'created_at' => '2024-11-21 14:26:05',
                'updated_at' => '2024-11-21 14:26:05',
            ),
            22 => 
            array (
                'id' => 24,
                'name' => 'force_delete',
                'guard_name' => 'category',
                'comment' => 'Полностью удалять категорию',
                'created_at' => '2024-11-21 14:26:36',
                'updated_at' => '2024-11-21 14:26:36',
            ),
            23 => 
            array (
                'id' => 25,
                'name' => 'force_delete',
                'guard_name' => 'page',
                'comment' => 'Полностью удалять страницы',
                'created_at' => '2024-11-21 14:27:38',
                'updated_at' => '2024-11-21 14:27:38',
            ),
            24 => 
            array (
                'id' => 26,
                'name' => 'force_delete',
                'guard_name' => 'user',
                'comment' => 'Полностью удалять пользователя',
                'created_at' => '2024-11-21 14:28:25',
                'updated_at' => '2024-11-21 14:28:25',
            ),
        ));
        
        
    }
}