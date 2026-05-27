<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->paginate(15);
        return view('admin.doctors', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctor-create');
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

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'doctor',
        ]);

        return redirect()->route('admin.doctors')->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function show($id)
    {
        $doctor = User::where('role', 'doctor')->with('timelineEvents')->findOrFail($id);
        $patientCount = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', $id))
            ->count();
        return view('admin.doctor-show', compact('doctor', 'patientCount'));
    }

    public function edit($id)
    {
        $doctor = User::where('role', 'doctor')->findOrFail($id);
        return view('admin.doctor-edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $doctor = User::where('role', 'doctor')->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $doctor->update($updateData);

        return redirect()->route('admin.doctors')->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }

    public function destroy($id)
    {
        $doctor = User::where('role', 'doctor')->findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors')->with('success', 'تم حذف الطبيب بنجاح');
    }

    public function patients($id)
    {
        $doctor = User::where('role', 'doctor')->findOrFail($id);
        $patients = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', $id))
            ->with('memberProfile')
            ->get();
        return view('admin.doctor-patients', compact('doctor', 'patients'));
    }
}
