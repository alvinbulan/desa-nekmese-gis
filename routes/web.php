<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\RegulationController;
use App\Http\Controllers\Admin\HeroSettingController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SidebarMenuController;
use App\Http\Controllers\Admin\FacilityPhotoController;
use App\Http\Controllers\Admin\AssetSectionController;

// Public Routes
Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.serve');

Route::get('/', [FacilityController::class, 'index'])->name('home');
Route::get('/fasilitas', [FacilityController::class, 'semuaFasilitas'])->name('fasilitas.semua');
Route::get('/aset-desa', [FacilityController::class, 'kategori'])->defaults('_filter', 'aset_desa')->name('fasilitas.aset-desa');
Route::get('/pendidikan', [FacilityController::class, 'kategori'])->defaults('_filter', 'Pendidikan')->name('fasilitas.pendidikan');
Route::get('/kesehatan', [FacilityController::class, 'kategori'])->defaults('_filter', 'Kesehatan')->name('fasilitas.kesehatan');
Route::get('/ibadah', [FacilityController::class, 'kategori'])->defaults('_filter', 'Tempat Ibadah')->name('fasilitas.ibadah');
Route::get('/pengumuman', [FacilityController::class, 'placeholder'])->name('fasilitas.pengumuman');
Route::get('/peraturan', [FacilityController::class, 'placeholder'])->name('fasilitas.peraturan');
Route::get('/api/facilities', [FacilityController::class, 'apiIndex'])->name('api.facilities.index');
Route::get('/api/facilities/{facility}', [FacilityController::class, 'apiShow'])->name('api.facilities.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('facilities', AdminFacilityController::class)
            ->parameters(['facilities' => 'facility']);
        Route::get('/facilities/{facility}/photos', [FacilityPhotoController::class, 'index'])->name('facilities.photos.index');
        Route::post('/facilities/{facility}/photos', [FacilityPhotoController::class, 'upload'])->name('facilities.photos.upload');
        Route::delete('/facilities/{facility}/photos/{photo}', [FacilityPhotoController::class, 'destroy'])->name('facilities.photos.destroy');
        Route::resource('announcements', AnnouncementController::class)
            ->parameters(['announcements' => 'announcement']);
        Route::resource('regulations', RegulationController::class)
            ->parameters(['regulations' => 'regulation']);

        Route::get('/settings/hero', [HeroSettingController::class, 'edit'])->name('settings.hero');
        Route::put('/settings/hero', [HeroSettingController::class, 'update'])->name('settings.hero.update');

        Route::get('/settings/aset-section', [AssetSectionController::class, 'edit'])->name('section-assets.edit');
        Route::put('/settings/aset-section', [AssetSectionController::class, 'update'])->name('section-assets.update');

        Route::get('/settings/site', [SiteSettingController::class, 'edit'])->name('settings.site');
        Route::put('/settings/site', [SiteSettingController::class, 'update'])->name('settings.site.update');

        Route::resource('sidebar-menus', SidebarMenuController::class)
            ->parameters(['sidebar-menus' => 'sidebarMenu'])
            ->only(['index', 'edit', 'update']);
    });
});
