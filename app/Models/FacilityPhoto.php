<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FacilityPhoto extends Model
{
    protected $fillable = ['facility_id', 'photo_path'];

    protected $appends = ['photo_url'];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if (str_starts_with($this->photo_path, 'http')) return $this->photo_path;
        return asset('images/' . ltrim($this->photo_path, '/'));
    }

    protected static function booted(): void
    {
        static::deleted(function (FacilityPhoto $photo) {
            if ($photo->photo_path && Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }
        });
    }
}
