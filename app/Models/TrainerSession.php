<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'member_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
