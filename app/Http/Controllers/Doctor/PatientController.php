<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', Auth::id()))
            ->with('memberProfile')
            ->get();
        return view('doctor.patients', compact('patients'));
    }

    public function create()
    {
        return view('doctor.patient-create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'goal' => 'nullable|string',
            'current_weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'body_fat' => 'nullable|numeric|min:0',
            'injuries' => 'nullable|string',
            'complaints' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => bcrypt($data['password']),
            'role' => 'member',
        ]);

        $profileData = ['user_id' => $user->id, 'doctor_id' => Auth::id()];
        if (!empty($data['goal'])) $profileData['goal'] = $data['goal'];
        if (!empty($data['current_weight'])) $profileData['current_weight'] = $data['current_weight'];
        if (!empty($data['height'])) $profileData['height'] = $data['height'];
        if (!empty($data['body_fat'])) $profileData['body_fat'] = $data['body_fat'];
        if (!empty($data['injuries'])) $profileData['injuries'] = $data['injuries'];
        if (!empty($data['complaints'])) $profileData['complaints'] = $data['complaints'];
        if ($request->hasFile('image')) {
            $profileData['image'] = $request->file('image')->store('members', 'public');
        }
        MemberProfile::create($profileData);

        TimelineEvent::create([
            'user_id' => Auth::id(),
            'related_user_id' => $user->id,
            'type' => 'member_added',
            'title' => 'مريض جديد',
            'description' => 'تم إضافة ' . $user->name . ' كمريض جديد',
            'metadata' => ['doctor_name' => Auth::user()->name],
        ]);

        return redirect()->route('doctor.patients')->with('success', 'تم إضافة المريض بنجاح');
    }

    public function show($id)
    {
        $member = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', Auth::id()))
            ->with('memberProfile', 'progressLogs', 'workoutPlans.days.exercises', 'nutritionPlans.meals')
            ->findOrFail($id);
        return view('doctor.patient-show', compact('member'));
    }
}
