<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\TimelineEvent;
use App\Models\WorkoutPlan;
use App\Models\NutritionPlan;
use App\Models\ProgressLog;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $profile = Auth::user()->memberProfile;
        $workoutPlan = WorkoutPlan::where('member_id', $userId)->where('is_active', true)
            ->with('days.exercises')->first();
        $nutritionPlan = NutritionPlan::where('member_id', $userId)->where('is_active', true)
            ->with('meals')->first();
        $latestProgress = ProgressLog::where('user_id', $userId)->latest()->first();
        $notificationSettings = NotificationSetting::firstOrCreate(['user_id' => $userId]);
        $timelineEvents = TimelineEvent::where('related_user_id', $userId)
            ->orWhere('user_id', $userId)
            ->latest()
            ->take(15)
            ->get();

        return view('member.dashboard', compact(
            'profile', 'workoutPlan', 'nutritionPlan', 'latestProgress', 'notificationSettings', 'timelineEvents'
        ));
    }
}
