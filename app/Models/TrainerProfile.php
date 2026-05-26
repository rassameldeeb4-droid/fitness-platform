<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'bio',
        'certification',
        'experience_years',
        'rating',
        'review_count',
        'available',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    public function reviews()
    {
        return $this->hasMany(TrainerReview::class, 'trainer_id');
    }
}