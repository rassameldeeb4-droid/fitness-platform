<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nutrition_plan_id',
        'meal_type',
        'time',
        'name',
        'description',
        'calories',
        'protein',
        'carbs',
        'fat',
        'items',
        'order',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_completed' => 'boolean',
        ];
    }

    public function plan()
    {
        return $this->belongsTo(NutritionPlan::class, 'nutrition_plan_id');
    }
}