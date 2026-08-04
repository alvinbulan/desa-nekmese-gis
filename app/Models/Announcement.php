<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['judul', 'isi', 'tipe', 'tanggal', 'gambar', 'active'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'active' => 'boolean',
        ];
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) return null;
        if (str_starts_with($this->gambar, 'http')) return $this->gambar;
        return asset('images/' . ltrim($this->gambar, '/'));
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}
