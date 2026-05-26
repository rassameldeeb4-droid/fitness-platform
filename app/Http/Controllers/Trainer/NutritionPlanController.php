<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use App\Models\TimelineEvent;
use App\Models\User;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionPlanController extends Controller
{
    public function create($memberId)
    {
        $member = User::findOrFail($memberId);
        return view('trainer.nutrition-create', compact('member'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:users,id',
            'goal' => 'required|string',
            'daily_calories' => 'required|integer',
            'protein' => 'required|integer',
            'carbs' => 'required|integer',
            'fat' => 'required|integer',
            'fiber' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);
        $data['trainer_id'] = Auth::id();
        $plan = NutritionPlan::create($data);
        TimelineEvent::create([
            'user_id' => Auth::id(),
            'related_user_id' => $data['member_id'],
            'type' => 'nutrition_plan_assigned',
            'title' => 'خطة غذائية جديدة',
            'description' => 'تم إرسال خطة غذائية لـ ' . User::find($data['member_id'])->name . ' — ' . $data['daily_calories'] . ' سعرة',
            'metadata' => ['plan_id' => $plan->id, 'goal' => $data['goal'], 'daily_calories' => $data['daily_calories']],
        ]);
        return redirect()->route('trainer.nutrition.show', $plan->id)->with('success', 'تم إنشاء الخطة الغذائية');
    }

    public function show($id)
    {
        $plan = NutritionPlan::with('member', 'meals')->findOrFail($id);
        return view('trainer.nutrition-show', compact('plan'));
    }
}
