<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $fillable = [
        'title', 'content', 'icon', 'order', 'is_active',
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
}
