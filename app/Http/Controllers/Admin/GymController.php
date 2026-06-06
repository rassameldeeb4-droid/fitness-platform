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
            'status' => 'nullable|string',
            'image' => 'nullable|string',
        ]));
        return redirect()->route('admin.gyms')->with('success', 'تم إضافة الصالة بنجاح');
    }

    public function edit($id)
    {
        $gym = Gym::findOrFail($id);
        return view('admin.gym-edit', compact('gym'));
    }

    public function update(Request $request, $id)
    {
        $gym = Gym::findOrFail($id);
        $gym->update($request->validate([
            'name' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'status' => 'nullable|string',
            'trainer_count' => 'nullable|integer',
            'image' => 'nullable|string',
        ]));
        return redirect()->route('admin.gyms')->with('success', 'تم تحديث الصالة بنجاح');
    }

    public function destroy($id)
    {
        Gym::findOrFail($id)->delete();
        return redirect()->route('admin.gyms')->with('success', 'تم حذف الصالة بنجاح');
    }
}
