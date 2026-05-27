<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TrainerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = User::where('role', 'trainer')->with('trainerProfile')->paginate(15);
        return view('admin.trainers', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainer-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'specialty' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'trainer',
        ]);

        TrainerProfile::create([
            'user_id' => $user->id,
            'specialty' => $data['specialty'] ?? 'عام',
            'available' => true,
        ]);

        return redirect()->route('admin.trainers')->with('success', 'تم إضافة المدرب');
    }

    public function show($id)
    {
        $trainer = User::where('role', 'trainer')->with('trainerProfile', 'trainerReviews')->findOrFail($id);
        return view('admin.trainer-show', compact('trainer'));
    }

    public function edit($id)
    {
        $trainer = User::where('role', 'trainer')->with('trainerProfile')->findOrFail($id);
        return view('admin.trainer-edit', compact('trainer'));
    }

    public function update(Request $request, $id)
    {
        $trainer = User::where('role', 'trainer')->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'specialty' => 'nullable|string|max:255',
            'available' => 'boolean',
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $trainer->update($updateData);

        if ($trainer->trainerProfile) {
            $trainer->trainerProfile->update([
                'specialty' => $data['specialty'] ?? $trainer->trainerProfile->specialty,
                'available' => $request->has('available'),
            ]);
        }

        return redirect()->route('admin.trainers')->with('success', 'تم تحديث بيانات المدرب');
    }

    public function destroy($id)
    {
        $trainer = User::where('role', 'trainer')->findOrFail($id);
        if ($trainer->trainerProfile) $trainer->trainerProfile->delete();
        $trainer->delete();

        return redirect()->route('admin.trainers')->with('success', 'تم حذف المدرب');
    }

    public function trainees($id)
    {
        $trainer = User::where('role', 'trainer')->findOrFail($id);
        $trainees = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', $id))
            ->with('memberProfile')
            ->get();
        return view('admin.trainer-trainees', compact('trainer', 'trainees'));
    }
}
