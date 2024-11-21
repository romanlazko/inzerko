<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'super-duper-admin',
                'guard_name' => 'web',
                'created_at' => '2024-11-07 11:52:12',
                'updated_at' => '2024-11-07 11:52:12',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'moderator',
                'guard_name' => 'web',
                'created_at' => '2024-11-18 14:18:37',
                'updated_at' => '2024-11-18 14:18:37',
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2024-11-18 17:04:33',
                'updated_at' => '2024-11-18 17:04:33',
            ),
        ));
        
        
    }
}