<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight',
        'body_fat',
        'muscle_mass',
        'bmi',
        'chest',
        'waist',
        'hips',
        'notes',
        'logged_date',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'logged_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}