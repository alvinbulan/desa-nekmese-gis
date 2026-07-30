<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_assets', function (Blueprint $table) {
            $table->id();
            $table->string('main_image')->nullable();
            $table->string('sub_image')->nullable();
            $table->timestamps();
        });

        DB::table('section_assets')->insert(['main_image' => null, 'sub_image' => null]);

        $oldMain = DB::table('settings')->where('key', 'aset_main_image')->value('value');
        $oldSub = DB::table('settings')->where('key', 'aset_sub_image')->value('value');
        if ($oldMain || $oldSub) {
            DB::table('section_assets')->where('id', 1)->update([
                'main_image' => $oldMain,
                'sub_image' => $oldSub,
            ]);
        }

        DB::table('settings')->whereIn('key', ['aset_main_image', 'aset_sub_image'])->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('section_assets');
    }
};
