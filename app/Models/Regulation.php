<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'file_path', 'tipe', 'tanggal', 'active'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
