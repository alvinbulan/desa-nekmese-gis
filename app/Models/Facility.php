<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    protected $fillable = ['nama', 'kategori', 'latitude', 'longitude', 'foto', 'foto_sekunder', 'alamat', 'deskripsi', 'active'];

    public function photos(): HasMany
    {
        return $this->hasMany(FacilityPhoto::class);
    }

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'active' => 'boolean',
        ];
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        if (str_starts_with($this->foto, 'http')) return $this->foto;
        return '/images/' . ltrim($this->foto, '/');
    }

    public function getFotoSekunderUrlAttribute(): ?string
    {
        if (!$this->foto_sekunder) return null;
        if (str_starts_with($this->foto_sekunder, 'http')) return $this->foto_sekunder;
        return '/images/' . ltrim($this->foto_sekunder, '/');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public static function kategoriList(): array
    {
        return [
            'kantor_desa' => 'Kantor Desa',
            'sekolah' => 'Sekolah',
            'gereja' => 'Gereja',
            'posyandu' => 'Posyandu',
            'lapangan' => 'Lapangan',
            'balai_desa' => 'Balai Desa',
            'tempat_ibadah' => 'Tempat Ibadah',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function jenisList(): array
    {
        return [
            'kantor_desa' => 'aset_desa',
            'balai_desa' => 'aset_desa',
            'lapangan' => 'aset_desa',
            'sekolah' => 'fasilitas_publik',
            'gereja' => 'fasilitas_publik',
            'posyandu' => 'fasilitas_publik',
            'tempat_ibadah' => 'fasilitas_publik',
            'lainnya' => 'fasilitas_publik',
        ];
    }

    public static function sektorList(): array
    {
        return [
            'kantor_desa' => 'Pemerintahan',
            'balai_desa' => 'Pemerintahan',
            'sekolah' => 'Pendidikan',
            'gereja' => 'Tempat Ibadah',
            'posyandu' => 'Kesehatan',
            'tempat_ibadah' => 'Tempat Ibadah',
            'lapangan' => 'Fasilitas Umum',
            'lainnya' => 'Fasilitas Umum',
        ];
    }
}
