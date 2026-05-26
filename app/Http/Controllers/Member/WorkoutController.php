<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function index()
    {
        $plan = WorkoutPlan::where('member_id', Auth::id())
            ->where('is_active', true)
            ->with('days.exercises')
            ->first();
        return view('member.workouts', compact('plan'));
    }

    public function markExerciseDone(Request $request)
    {
        $request->validate([
            'day_id' => 'required|exists:workout_plan_days,id',
            'exercise_id' => 'required|exists:exercise_workout_plan_day,id',
        ]);
        // Mark exercise as completed
        // DB::table('exercise_workout_plan_day')->where('id', $request->exercise_id)->update(['is_completed' => true]);
        return response()->json(['success' => true]);
    }
}
