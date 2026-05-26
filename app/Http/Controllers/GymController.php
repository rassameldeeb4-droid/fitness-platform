<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymController extends Controller
{
    public function index()
    {
        $gyms = Gym::where('status', 'active')->withCount('users')->get();
        return view('gyms.index', compact('gyms'));
    }

    public function rate(Request $request)
    {
        $data = $request->validate([
            'gym_id' => 'required|exists:gyms,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string',
        ]);
        $data['user_id'] = Auth::id();
        GymRating::updateOrCreate(
            ['gym_id' => $data['gym_id'], 'user_id' => Auth::id()],
            $data
        );
        return back()->with('success', 'تم إضافة التقييم');
    }
}
