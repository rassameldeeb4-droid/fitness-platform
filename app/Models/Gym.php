<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'phone',
        'capacity',
        'trainer_count',
        'status',
        'image',
    ];

    public function ratings()
    {
        return $this->hasMany(GymRating::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'gym_id');
    }
}