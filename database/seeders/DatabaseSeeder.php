<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            FacilitySeeder::class,
            AdminUserSeeder::class,
            SettingSeeder::class,
            SidebarMenuSeeder::class,
        ]);

        if (file_exists(database_path('database.sqlite'))) {
            Artisan::call('db:copy-sqlite-to-mysql');
        }
    }
}
