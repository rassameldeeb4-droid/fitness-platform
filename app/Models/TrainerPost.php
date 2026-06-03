<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerPost extends Model
{
    use HasFactory;

    protected $fillable = ['trainer_id', 'content', 'image'];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }
}
