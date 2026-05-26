<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProgressLog;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $trainees = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', Auth::id()))
            ->with('memberProfile', 'progressLogs')
            ->get();
        return view('trainer.progress', compact('trainees'));
    }

    public function show($memberId)
    {
        $member = User::with('memberProfile', 'progressLogs')->findOrFail($memberId);
        $logs = ProgressLog::where('user_id', $memberId)->orderBy('logged_date')->get();
        return view('trainer.progress-show', compact('member', 'logs'));
    }
}
