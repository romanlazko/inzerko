<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::factory(100)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(GeoTableSeeder::class);
        $this->call(AttributeSectionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(AttributeCategoryTableSeeder::class);
        $this->call(AttributeOptionsTableSeeder::class);
        $this->call(AttributeGroupsTableSeeder::class);
        $this->call(SortingsTableSeeder::class);
    }
}
