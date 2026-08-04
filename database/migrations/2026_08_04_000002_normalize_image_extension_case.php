<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'facilities' => ['foto', 'foto_sekunder'],
            'facility_photos' => ['photo_path'],
            'sidebar_menus' => ['banner_image_url', 'background_image_url'],
            'section_assets' => ['main_image', 'sub_image'],
            'announcements' => ['gambar'],
        ];

        foreach ($tables as $table => $columns) {
            try {
                foreach ($columns as $column) {
                    DB::table($table)
                        ->whereNotNull($column)
                        ->where($column, 'not like', 'http%')
                        ->where($column, 'like', '%.%')
                        ->whereRaw("LOWER(SUBSTRING_INDEX(`{$column}`, '.', -1)) <> SUBSTRING_INDEX(`{$column}`, '.', -1)")
                        ->update([
                            $column => DB::raw(
                                "CONCAT(LEFT(`{$column}`, CHAR_LENGTH(`{$column}`) - CHAR_LENGTH(SUBSTRING_INDEX(`{$column}`, '.', -1))), LOWER(SUBSTRING_INDEX(`{$column}`, '.', -1)))"
                            ),
                        ]);
                }
            } catch (\Throwable $e) {
                // Table/column may not exist in some environments.
            }
        }

        try {
            foreach (['hero_bg_image', 'logo_url', 'favicon_url'] as $key) {
                DB::table('settings')
                    ->where('key', $key)
                    ->where('value', 'not like', 'http%')
                    ->where('value', 'like', '%.%')
                    ->whereRaw("LOWER(SUBSTRING_INDEX(`value`, '.', -1)) <> SUBSTRING_INDEX(`value`, '.', -1)")
                    ->update([
                        'value' => DB::raw(
                            "CONCAT(LEFT(`value`, CHAR_LENGTH(`value`) - CHAR_LENGTH(SUBSTRING_INDEX(`value`, '.', -1))), LOWER(SUBSTRING_INDEX(`value`, '.', -1)))"
                        ),
                    ]);
            }
        } catch (\Throwable $e) {
            // skip
        }
    }

    public function down(): void
    {
        // No reverse operation.
    }
};