<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarMenu extends Model
{
    protected $fillable = [
        'menu_name', 'icon_name', 'target_link',
        'banner_image_url', 'background_image_url', 'heading_text',
        'default_gradient', 'sort_order', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_image_url) return null;
        if (str_starts_with($this->banner_image_url, 'http')) return $this->banner_image_url;
        return '/images/' . ltrim($this->banner_image_url, '/') . '?v=' . ($this->updated_at?->timestamp ?? time());
    }

    public function getBackgroundUrlAttribute(): ?string
    {
        if (!$this->background_image_url) return null;
        if (str_starts_with($this->background_image_url, 'http')) return $this->background_image_url;
        return '/images/' . ltrim($this->background_image_url, '/') . '?v=' . ($this->updated_at?->timestamp ?? time());
    }

    public function scopeActive($q) { return $q->where('active', true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order')->orderBy('menu_name'); }
}
