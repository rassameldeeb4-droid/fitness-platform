<?php

namespace App\Http\Controllers;

use App\Models\NutritionPlan;
use App\Models\NutritionMeal;
use App\Models\TimelineEvent;
use App\Models\TrainerWhatsAppConfig;
use App\Models\User;
use Illuminate\Http\Request;

class NutritionSaveController extends Controller
{
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
            'water' => 'nullable|string',
            'notes' => 'nullable|string',
            'meals' => 'required|array',
            'meals.*.name' => 'required|string',
            'meals.*.time' => 'nullable|string',
            'meals.*.calories' => 'required|integer',
            'meals.*.protein' => 'nullable|integer',
            'meals.*.carbs' => 'nullable|integer',
            'meals.*.fat' => 'nullable|integer',
            'meals.*.items' => 'nullable|string',
            'meals.*.vitamins' => 'nullable|string',
            'meals.*.minerals' => 'nullable|string',
        ]);

        $plan = NutritionPlan::create([
            'trainer_id' => auth()->id(),
            'member_id' => $data['member_id'],
            'name' => 'AI نظام غذائي',
            'goal' => $data['goal'],
            'daily_calories' => $data['daily_calories'],
            'protein' => $data['protein'],
            'carbs' => $data['carbs'],
            'fat' => $data['fat'],
            'fiber' => $data['fiber'] ?? 0,
            'notes' => ($data['water'] ? "الماء: {$data['water']}\n" : '') . ($data['notes'] ?? ''),
        ]);

        foreach ($data['meals'] as $i => $m) {
            NutritionMeal::create([
                'nutrition_plan_id' => $plan->id,
                'meal_type' => $m['name'],
                'time' => $m['time'] ?? '',
                'name' => $m['name'],
                'description' => ($m['vitamins'] ? "فيتامينات: {$m['vitamins']}\n" : '') . ($m['minerals'] ? "معادن: {$m['minerals']}\n" : ''),
                'calories' => $m['calories'],
                'protein' => $m['protein'] ?? 0,
                'carbs' => $m['carbs'] ?? 0,
                'fat' => $m['fat'] ?? 0,
                'items' => array_filter(explode("\n", str_replace(['• '], '', $m['items']))),
                'order' => $i + 1,
            ]);
        }

        $member = User::find($data['member_id']);

        TimelineEvent::create([
            'user_id' => auth()->id(),
            'related_user_id' => $data['member_id'],
            'type' => 'nutrition_plan_assigned',
            'title' => 'نظام غذائي ذكي',
            'description' => 'تم إنشاء نظام غذائي بـ ' . $data['daily_calories'] . ' سعرة لـ ' . $member->name,
            'metadata' => ['plan_id' => $plan->id, 'goal' => $data['goal'], 'daily_calories' => $data['daily_calories']],
        ]);

        $waConfig = TrainerWhatsAppConfig::where('trainer_id', auth()->id())->where('is_connected', true)->where('notify_nutrition', true)->first();
        if ($waConfig && $member->phone) {
            $msg = "🍽 *نظام غذائي جديد*\n\n"
                 . "{$member->name}، تم إعداد خطة غذائية لك بـ {$data['daily_calories']} سعرة/يوم\n"
                 . "الهدف: {$data['goal']}\n"
                 . "تفضّل بمراجعة تطبيق FitCore للتفاصيل الكاملة ✅";
            $waConfig->sendMessage($member->phone, $msg);
        }

        return response()->json(['success' => true, 'plan_id' => $plan->id]);
    }
}
