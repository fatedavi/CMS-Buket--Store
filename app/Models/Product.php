<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'description',
        'image', 'images', 'badge', 'status',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return '';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return Storage::url($this->image);
    }

    public function getImagesUrlAttribute(): array
    {
        if (!$this->images) return [];
        return array_map(fn($img) => match (true) {
            !$img => '',
            str_starts_with($img, 'http') => $img,
            default => Storage::url($img),
        }, $this->images);
    }
}
