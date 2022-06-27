<?php

namespace Database\Seeders;

use App\Models\Layout;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            DistrictSeeder::class,
            WidgetSeeder::class,
            LayoutSeeder::class,
            UserSeeder::class,
        ]);


        // \App\Models\User::factory(10)->create();
    }
}
