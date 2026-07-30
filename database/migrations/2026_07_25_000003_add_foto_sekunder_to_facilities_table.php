<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('foto_sekunder')->nullable()->after('foto');
            $table->boolean('active')->default(true)->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['foto_sekunder', 'active']);
        });
    }
};
