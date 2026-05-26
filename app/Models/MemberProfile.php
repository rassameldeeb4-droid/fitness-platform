<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trainer_id',
        'doctor_id',
        'gym_id',
        'goal',
        'height',
        'current_weight',
        'target_weight',
        'body_fat',
        'muscle_mass',
        'activity_level',
        'birth_date',
        'bmi',
        'progress_percentage',
        'workout_days_per_week',
        'image',
        'injuries',
        'complaints',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}