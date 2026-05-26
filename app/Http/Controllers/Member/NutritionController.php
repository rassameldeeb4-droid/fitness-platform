<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\NutritionPlan;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionController extends Controller
{
    public function index()
    {
        $plan = NutritionPlan::where('member_id', Auth::id())
            ->where('is_active', true)
            ->with('meals')
            ->first();
        return view('member.nutrition', compact('plan'));
    }

    public function analyzeFood()
    {
        return view('member.food-analyzer');
    }

    public function analyzeFoodPost(Request $request, AiService $aiService)
    {
        $request->validate(['query' => 'required|string|max:500']);
        $result = $aiService->analyzeFood($request->query);
        return response()->json($result);
    }
}
