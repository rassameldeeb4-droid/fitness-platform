<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Services\AiService;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::where('is_active', true)->get();
        $categories = ['الكل', 'بروتين', 'كارب', 'دهون صحية', 'فاكهة', 'خضار', 'ألبان', 'بقوليات'];
        return view('foods.index', compact('foods', 'categories'));
    }

    public function analyze()
    {
        return view('foods.analyze');
    }

    public function analyzePost(Request $request, AiService $aiService)
    {
        $request->validate(['query' => 'required|string|max:500']);
        $result = $aiService->analyzeFood($request->query);
        return response()->json($result);
    }
}
