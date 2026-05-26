<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index()
    {
        $gyms = Gym::withCount('users')->get();
        return view('admin.gyms', compact('gyms'));
    }

    public function store(Request $request)
    {
        Gym::create($request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'capacity' => 'nullable|integer',
        ]));
        return redirect()->route('admin.gyms')->with('success', 'تم إضافة الصالة بنجاح');
    }
}
