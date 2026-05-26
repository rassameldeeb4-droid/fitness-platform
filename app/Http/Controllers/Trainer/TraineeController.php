<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\MemberProfile;
use App\Models\TimelineEvent;
use App\Models\User;
use App\Models\ProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TraineeController extends Controller
{
    public function index()
    {
        $trainees = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', Auth::id()))
            ->with('memberProfile', 'progressLogs')
            ->get();
        return view('trainer.trainees', compact('trainees'));
    }

    public function create()
    {
        return view('trainer.trainee-create');
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

        $profileData = ['user_id' => $user->id, 'trainer_id' => Auth::id()];
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
            'title' => 'متدرب جديد',
            'description' => 'تم إضافة ' . $user->name . ' كمتدرب جديد',
            'metadata' => ['trainer_name' => Auth::user()->name],
        ]);

        return redirect()->route('trainer.trainees')->with('success', 'تم إضافة المتدرب بنجاح');
    }

    public function show($id)
    {
        $member = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', Auth::id()))
            ->with('memberProfile', 'progressLogs', 'workoutPlans.days.exercises', 'nutritionPlans.meals', 'timelineEvents')
            ->findOrFail($id);
        return view('trainer.trainee-show', compact('member'));
    }
}
