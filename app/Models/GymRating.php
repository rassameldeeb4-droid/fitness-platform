<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'user_id',
        'rating',
        'review',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'rating' => 'decimal:2',
        ];
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}