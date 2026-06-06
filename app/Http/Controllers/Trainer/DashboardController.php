<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TimelineEvent;
use App\Models\WorkoutPlan;
use App\Models\NutritionPlan;
use App\Models\ProgressLog;
use App\Models\TrainerSession;
use App\Models\TrainerPost;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $trainerId = Auth::id();
        $memberCount = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', $trainerId))
            ->count();
        
        $trainees = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', $trainerId))
            ->with('memberProfile')
            ->get();

        $recentEvents = TimelineEvent::where('related_user_id', $trainerId)
            ->latest()
            ->take(10)
            ->get();

        $workoutPlansCount = WorkoutPlan::where('trainer_id', $trainerId)->count();
        $nutritionPlansCount = NutritionPlan::where('trainer_id', $trainerId)->count();

        $todaySessions = TrainerSession::where('trainer_id', $trainerId)
            ->whereDate('scheduled_at', today())
            ->with('member')
            ->orderBy('scheduled_at')
            ->get();

        $latestPosts = TrainerPost::where('trainer_id', $trainerId)
            ->latest()
            ->take(5)
            ->get();

        return view('trainer.dashboard', compact(
            'memberCount', 'trainees', 'recentEvents', 'workoutPlansCount',
            'nutritionPlansCount', 'todaySessions', 'latestPosts'
        ));
    }
}
