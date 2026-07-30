<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sidebar_menus', function (Blueprint $table) {
            $table->string('background_image_url')->nullable()->after('banner_image_url');
            $table->text('heading_text')->nullable()->after('background_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('sidebar_menus', function (Blueprint $table) {
            $table->dropColumn(['background_image_url', 'heading_text']);
        });
    }
};
