<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\TimelineEvent;
use App\Models\WorkoutPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutPlanController extends Controller
{
    public function create($memberId)
    {
        $member = User::findOrFail($memberId);
        return view('doctor.workout-create', compact('member'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:users,id',
            'name' => 'nullable|string',
            'goal' => 'nullable|string',
            'level' => 'required|string',
            'days_per_week' => 'required|integer|min:1|max:7',
            'notes' => 'nullable|string',
        ]);
        $data['trainer_id'] = Auth::id();
        $plan = WorkoutPlan::create($data);
        TimelineEvent::create([
            'user_id' => Auth::id(),
            'related_user_id' => $data['member_id'],
            'type' => 'workout_plan_assigned',
            'title' => 'خطة تدريبية جديدة',
            'description' => 'تم إرسال خطة تدريبية لـ ' . User::find($data['member_id'])->name . ' — ' . $data['days_per_week'] . ' أيام بالأسبوع',
            'metadata' => ['plan_id' => $plan->id, 'goal' => $data['goal'] ?? '', 'days_per_week' => $data['days_per_week'], 'level' => $data['level']],
        ]);
        return redirect()->route('doctor.workout.show', $plan->id)->with('success', 'تم إنشاء خطة التدريب');
    }

    public function show($id)
    {
        $plan = WorkoutPlan::with('member', 'days.exercises')->findOrFail($id);
        return view('doctor.workout-show', compact('plan'));
    }
}
