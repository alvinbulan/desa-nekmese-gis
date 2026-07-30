<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateSqliteToMysql extends Command
{
    protected $signature = 'db:copy-sqlite-to-mysql';
    protected $description = 'Pindahkan seluruh data dari database.sqlite ke MySQL';

    public function handle()
    {
        $sqlitePath = database_path('database.sqlite');

        if (!file_exists($sqlitePath)) {
            $this->error("File database.sqlite tidak ditemukan di folder database/");
            return;
        }

        $this->info("Memulai penyalinan data dari SQLite ke MySQL...");

        config(['database.connections.sqlite_old' => [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
            'prefix' => '',
        ]]);

        $tables = [
            'facility_photos',
            'facilities',
            'users',
            'announcements',
            'regulations',
            'settings',
            'sidebar_menus',
            'section_assets',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            if (!Schema::connection('sqlite_old')->hasTable($table)) {
                $this->warn("Tabel '$table' tidak ditemukan di SQLite, dilewati.");
                continue;
            }

            $rows = DB::connection('sqlite_old')->table($table)->get();

            if ($rows->count() > 0) {
                DB::table($table)->truncate();

                $chunks = $rows->chunk(100);
                foreach ($chunks as $chunk) {
                    $insertData = $chunk->map(function ($row) {
                        return (array) $row;
                    })->toArray();
                    DB::table($table)->insert($insertData);
                }

                $this->info("Berhasil menyalin {$rows->count()} baris ke tabel: {$table}");
            } else {
                $this->line("Tabel '$table' kosong, dilewati.");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info('Migrasi data dari SQLite ke MySQL SELESAI!');
    }
}
