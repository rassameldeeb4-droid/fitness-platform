<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\TimelineEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $logs = ProgressLog::where('user_id', Auth::id())->orderBy('logged_date', 'desc')->get();
        $profile = Auth::user()->memberProfile;
        return view('member.progress', compact('logs', 'profile'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'weight' => 'nullable|numeric',
            'body_fat' => 'nullable|numeric',
            'muscle_mass' => 'nullable|numeric',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'logged_date' => 'required|date',
        ]);
        $data['user_id'] = Auth::id();
        $log = ProgressLog::create($data);
        $member = Auth::user();
        if ($member->memberProfile && $member->memberProfile->trainer_id) {
            TimelineEvent::create([
                'user_id' => Auth::id(),
                'related_user_id' => $member->memberProfile->trainer_id,
                'type' => 'progress_logged',
                'title' => 'تحديث القياسات',
                'description' => $member->name . ' سجّل قياساته الجديدة' . ($data['weight'] ? ' — الوزن: ' . $data['weight'] . ' كغ' : ''),
                'metadata' => ['weight' => $data['weight'] ?? null, 'body_fat' => $data['body_fat'] ?? null],
            ]);
        }
        return back()->with('success', 'تم تسجيل القياسات');
    }
}
