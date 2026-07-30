<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'hero_bg_image'],
            ['value' => null]
        );
        Setting::firstOrCreate(
            ['key' => 'logo_url'],
            ['value' => null]
        );
        Setting::firstOrCreate(
            ['key' => 'favicon_url'],
            ['value' => null]
        );
    }
}
