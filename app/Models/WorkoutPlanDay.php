<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlanDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'day_name',
        'focus',
        'order',
        'is_completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function plan()
    {
        return $this->belongsTo(WorkoutPlan::class, 'workout_plan_id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_workout_plan_day')
            ->withPivot(['sets', 'reps', 'rest_seconds', 'order', 'is_completed'])
            ->withTimestamps();
    }
}