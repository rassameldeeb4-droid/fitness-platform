<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerReel extends Model
{
    use HasFactory;

    protected $fillable = ['trainer_id', 'title', 'video', 'description'];

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
