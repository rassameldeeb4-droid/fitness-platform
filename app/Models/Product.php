<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'brand',
        'category',
        'description',
        'price',
        'old_price',
        'image',
        'rating',
        'review_count',
        'badge',
        'is_active',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
            'rating' => 'decimal:2',
        ];
    }
}