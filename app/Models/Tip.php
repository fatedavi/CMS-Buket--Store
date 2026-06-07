<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tip extends Model
{
    protected $fillable = [
        'title', 'content', 'icon', 'background_image', 'order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public static function iconOptions(): array
    {
        return [
            'lightbulb', 'flower', 'target', 'sparkle', 'palette', 'leaf',
            'calendar', 'heart', 'star', 'gift', 'camera', 'clock', 'chat',
            'truck', 'cherry-blossom', 'map-pin', 'phone', 'wave', 'diamond',
            'fire', 'moon', 'sun', 'droplet', 'scissors', 'book', 'music',
        ];
    }

    public function getBackgroundImageUrlAttribute(): string
    {
        if (! $this->background_image) {
            return '';
        }
        if (str_starts_with($this->background_image, 'http')) {
            return $this->background_image;
        }

        return Storage::url($this->background_image);
    }
}
