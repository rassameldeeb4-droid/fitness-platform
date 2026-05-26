<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'member_id',
        'name',
        'goal',
        'daily_calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'notes',
        'is_active',
    ];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function meals()
    {
        return $this->hasMany(NutritionMeal::class);
    }
}