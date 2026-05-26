<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'category',
        'muscle_group',
        'sets_default',
        'reps_default',
        'description',
        'video_url',
        'difficulty',
        'equipment',
        'calories_per_set',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function workoutPlanDays()
    {
        return $this->belongsToMany(WorkoutPlanDay::class, 'exercise_workout_plan_day')
            ->withPivot(['sets', 'reps', 'rest_seconds', 'order', 'is_completed'])
            ->withTimestamps();
    }
}