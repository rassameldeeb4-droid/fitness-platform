<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MemberProfile;
use App\Models\Gym;
use App\Models\Package;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->with('memberProfile', 'subscriptions.package')->paginate(15);
        return view('admin.members', compact('members'));
    }

    public function create()
    {
        $gyms = Gym::all();
        $packages = Package::all();
        return view('admin.member-create', compact('gyms', 'packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
            'gym_id' => 'nullable|exists:gyms,id',
            'goal' => 'nullable|string',
            'current_weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'body_fat' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => bcrypt($data['password']),
            'role' => 'member',
            'gym_id' => $data['gym_id'] ?? null,
        ]);

        $profileData = ['user_id' => $user->id];
        if (!empty($data['goal'])) $profileData['goal'] = $data['goal'];
        if (!empty($data['current_weight'])) $profileData['current_weight'] = $data['current_weight'];
        if (!empty($data['height'])) $profileData['height'] = $data['height'];
        if (!empty($data['body_fat'])) $profileData['body_fat'] = $data['body_fat'];
        MemberProfile::create($profileData);

        return redirect()->route('admin.members')->with('success', 'تم إضافة المشترك بنجاح');
    }

    public function show($id)
    {
        $member = User::where('role', 'member')->with('memberProfile', 'subscriptions.package', 'progressLogs')->findOrFail($id);
        return view('admin.member-show', compact('member'));
    }
}
