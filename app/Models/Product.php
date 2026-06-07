<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'description', 'price',
        'image', 'images', 'badge', 'status',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return '';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    public function getImagesUrlAttribute(): array
    {
        if (! $this->images) {
            return [];
        }

        return array_map(fn ($img) => match (true) {
            ! $img => '',
            str_starts_with($img, 'http') => $img,
            default => Storage::url($img),
        }, $this->images);
    }
}
