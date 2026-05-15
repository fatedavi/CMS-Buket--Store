<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
