<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function showTrainer($id)
    {
        $trainer = User::where('role', 'trainer')
            ->with('trainerProfile', 'trainerReviews.member')
            ->findOrFail($id);
        return view('profiles.trainer', compact('trainer'));
    }

    public function showMember($id)
    {
        $member = User::where('role', 'member')
            ->with('memberProfile', 'progressLogs')
            ->findOrFail($id);
        return view('profiles.member', compact('member'));
    }
}
