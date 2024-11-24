<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HtmlLayoutsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('html_layouts')->delete();
        
        \DB::table('html_layouts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => 'sablon-telegram',
                'name' => 'Шаблон телеграм',
                'blade' => '<b>{{ 
$announcement->title
}}</b>

{{ $announcement->description }}

Зарплата: {{ 
$announcement->price
}}

<b>{{
$announcement->categories
?->pluck(\'name\')
?->map(fn ($value) => str()
->of($value)
->lower()
->replace(\' \', \'_\')
->prepend(\'#\')
)
?->implode(\' \')
}}</b>',
            'is_active' => NULL,
            'created_at' => '2024-11-24 12:50:32',
            'updated_at' => '2024-11-24 13:15:16',
            'deleted_at' => NULL,
        ),
        1 => 
        array (
            'id' => 2,
            'slug' => '2-sablon',
            'name' => '2 шаблон',
            'blade' => '111',
            'is_active' => NULL,
            'created_at' => '2024-11-24 13:05:27',
            'updated_at' => '2024-11-24 13:21:21',
            'deleted_at' => '2024-11-24 13:21:21',
        ),
    ));
        
        
    }
}