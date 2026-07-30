<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SidebarMenu;

class SidebarMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['menu_name' => 'Beranda',       'icon_name' => 'fas fa-home',           'target_link' => '/',               'sort_order' => 1,  'default_gradient' => 'background:linear-gradient(135deg,#0D9488,#0F766E)'],
            ['menu_name' => 'Aset Desa',     'icon_name' => 'fas fa-landmark',        'target_link' => '/aset-desa',      'sort_order' => 10, 'default_gradient' => 'background:linear-gradient(135deg,#1e3a5f,#2d5a87)'],
            ['menu_name' => 'Pendidikan',    'icon_name' => 'fas fa-school',          'target_link' => '/pendidikan',     'sort_order' => 20, 'default_gradient' => 'background:linear-gradient(135deg,#0f766e,#14b8a6)'],
            ['menu_name' => 'Kesehatan',     'icon_name' => 'fas fa-heartbeat',       'target_link' => '/kesehatan',      'sort_order' => 30, 'default_gradient' => 'background:linear-gradient(135deg,#b91c1c,#ef4444)'],
            ['menu_name' => 'Tempat Ibadah', 'icon_name' => 'fas fa-place-of-worship','target_link' => '/ibadah',         'sort_order' => 40, 'default_gradient' => 'background:linear-gradient(135deg,#92400e,#d97706)'],
        ];

        foreach ($menus as $m) {
            SidebarMenu::firstOrCreate(
                ['menu_name' => $m['menu_name']],
                $m
            );
        }
    }
}
