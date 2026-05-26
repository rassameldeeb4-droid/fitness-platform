<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::where('is_active', true)->get();
        $categories = ['الكل', 'صدر', 'ظهر', 'أرجل', 'ذراع', 'كتف', 'بطن'];
        return view('exercises.index', compact('exercises', 'categories'));
    }

    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercises.show', compact('exercise'));
    }
}
