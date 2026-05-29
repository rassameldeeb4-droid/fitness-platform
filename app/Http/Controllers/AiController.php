<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;

class AiController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generateNutrition(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'goal' => 'required|string',
            'activity_level' => 'required|string',
            'body_fat' => 'nullable|numeric',
            'workout_days' => 'required|integer',
            'wrist' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'job' => 'nullable|string',
        ]);

        $result = $this->aiService->generateNutritionPlan($data);
        return response()->json($result);
    }

    public function generateWorkout(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'goal' => 'required|string',
            'level' => 'required|string',
            'days' => 'required|integer',
        ]);

        $result = $this->aiService->generateWorkoutPlan($data);
        return response()->json($result);
    }

    public function analyzeFood(Request $request)
    {
        $data = $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $result = $this->aiService->analyzeFood($data['query']);
        return response()->json($result);
    }
}
