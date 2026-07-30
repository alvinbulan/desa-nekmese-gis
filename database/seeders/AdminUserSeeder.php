<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@nekmese.desa',
            'password' => bcrypt('admin123'),
        ]);

        $this->command->info('Admin user created: admin@nekmese.desa / admin123');
    }
}
