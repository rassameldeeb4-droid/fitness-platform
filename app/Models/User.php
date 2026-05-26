<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gym_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleAttribute()
    {
        return $this->attributes['role'] ?? $this->roles->first()?->name;
    }

    public function memberProfile()
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function trainerProfile()
    {
        return $this->hasOne(TrainerProfile::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'member_id')
            ->orWhere('trainer_id', $this->id);
    }

    public function timelineEvents()
    {
        return $this->hasMany(TimelineEvent::class);
    }

    public function progressLogs()
    {
        return $this->hasMany(ProgressLog::class);
    }

    public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTrainer(): bool
    {
        return $this->hasRole('trainer');
    }

    public function isMember(): bool
    {
        return $this->hasRole('member');
    }

    public function scopeAdmin($query)
    {
        return $query->role('admin');
    }

    public function scopeTrainer($query)
    {
        return $query->role('trainer');
    }

    public function scopeMember($query)
    {
        return $query->role('member');
    }
}