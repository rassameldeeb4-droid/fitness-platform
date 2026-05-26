<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TimelineEvent;
use App\Models\WorkoutPlan;
use App\Models\NutritionPlan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        $patientCount = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', $doctorId))
            ->count();

        $recentEvents = TimelineEvent::where('related_user_id', $doctorId)
            ->latest()
            ->take(10)
            ->get();

        $workoutPlansCount = WorkoutPlan::where('trainer_id', $doctorId)->count();
        $nutritionPlansCount = NutritionPlan::where('trainer_id', $doctorId)->count();

        return view('doctor.dashboard', compact(
            'patientCount', 'recentEvents', 'workoutPlansCount', 'nutritionPlansCount'
        ));
    }
}
