<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Facility::create([
            'nama' => 'Kantor Desa Nekmese',
            'kategori' => 'kantor_desa',
            'latitude' => -10.193,
            'longitude' => 123.715,
            'foto' => 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese, Kec. Amarasi Selatan, Kab. Kupang, NTT',
            'deskripsi' => 'Pusat pemerintahan dan pelayanan administrasi Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'SD Negeri Nekmese',
            'kategori' => 'sekolah',
            'latitude' => -10.190,
            'longitude' => 123.718,
            'foto' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Sekolah Dasar Negeri yang melayani pendidikan tingkat dasar di Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'SMP Negeri Satu Atap Nekmese',
            'kategori' => 'sekolah',
            'latitude' => -10.191,
            'longitude' => 123.719,
            'foto' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Sekolah Menengah Pertama yang melayani pendidikan tingkat menengah pertama.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Gereja GMIT Nekmese',
            'kategori' => 'gereja',
            'latitude' => -10.1925,
            'longitude' => 123.714,
            'foto' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Gereja GMIT sebagai tempat ibadah umat Kristen Protestan di Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Posyandu Nekmese',
            'kategori' => 'posyandu',
            'latitude' => -10.1935,
            'longitude' => 123.716,
            'foto' => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Pos Pelayanan Terpadu untuk kesehatan ibu, balita, dan lansia.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Pustu Nekmese',
            'kategori' => 'posyandu',
            'latitude' => -10.194,
            'longitude' => 123.7165,
            'foto' => 'https://images.unsplash.com/photo-1586773860418-d37222d8fce3?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Puskesmas Pembantu yang menyediakan layanan kesehatan dasar bagi masyarakat.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Lapangan Umum Desa Nekmese',
            'kategori' => 'lapangan',
            'latitude' => -10.192,
            'longitude' => 123.717,
            'foto' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Lapangan serbaguna yang digunakan untuk olahraga sepak bola, voli, dan kegiatan desa. Tempat penyelenggaraan turnamen JPW Amarasi Cup.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Balai Desa Nekmese',
            'kategori' => 'balai_desa',
            'latitude' => -10.1932,
            'longitude' => 123.7155,
            'foto' => 'https://images.unsplash.com/photo-1569529465841-dfecdab7503b?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Balai pertemuan serbaguna untuk musyawarah desa, acara adat, dan kegiatan kemasyarakatan.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Gereja Katolik Nekmese',
            'kategori' => 'tempat_ibadah',
            'latitude' => -10.1915,
            'longitude' => 123.7135,
            'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
            'alamat' => 'Dusun 2, Desa Nekmese',
            'deskripsi' => 'Tempat ibadah bagi umat Katolik di wilayah Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Mushola Al-Ikhlas',
            'kategori' => 'tempat_ibadah',
            'latitude' => -10.1928,
            'longitude' => 123.7145,
            'foto' => 'https://images.unsplash.com/photo-1566140967404-b8b3932483f5?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Mushola sebagai tempat ibadah umat Muslim di Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'TK Negeri Pembina Nekmese',
            'kategori' => 'sekolah',
            'latitude' => -10.1905,
            'longitude' => 123.7175,
            'foto' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400',
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Taman Kanak-Kanak untuk pendidikan anak usia dini di Desa Nekmese.',
        ]);

        \App\Models\Facility::create([
            'nama' => 'Lapangan Voli Nekmese',
            'kategori' => 'lapangan',
            'latitude' => -10.1923,
            'longitude' => 123.7168,
            'foto' => null,
            'alamat' => 'Dusun 1, Desa Nekmese',
            'deskripsi' => 'Lapangan voli yang digunakan oleh pemuda desa untuk berolahraga dan turnamen.',
        ]);
    }
}
