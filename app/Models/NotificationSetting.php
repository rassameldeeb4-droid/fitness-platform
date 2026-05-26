<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workout_reminder',
        'workout_reminder_minutes',
        'meal_reminder',
        'meal_reminder_minutes',
        'water_reminder',
        'water_interval_minutes',
        'sleep_reminder',
        'sleep_time',
        'measurement_reminder',
        'measurement_day',
        'supplement_reminder',
        'whatsapp_enabled',
        'whatsapp_phone',
        'whatsapp_api_token',
    ];

    protected function casts(): array
    {
        return [
            'workout_reminder' => 'boolean',
            'meal_reminder' => 'boolean',
            'water_reminder' => 'boolean',
            'sleep_reminder' => 'boolean',
            'measurement_reminder' => 'boolean',
            'supplement_reminder' => 'boolean',
            'whatsapp_enabled' => 'boolean',
        ];
    }
}