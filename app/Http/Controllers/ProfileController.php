<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TrainerPost;
use App\Models\TrainerReel;

class ProfileController extends Controller
{
    public function showTrainer($id)
    {
        $trainer = User::where('role', 'trainer')
            ->with('trainerProfile', 'trainerReviews.member')
            ->findOrFail($id);
        $posts = TrainerPost::where('trainer_id', $id)
            ->with('comments.user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        $reels = TrainerReel::where('trainer_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return view('profiles.trainer', compact('trainer', 'posts', 'reels'));
    }

    public function showMember($id)
    {
        $member = User::where('role', 'member')
            ->with('memberProfile', 'progressLogs')
            ->findOrFail($id);
        return view('profiles.member', compact('member'));
    }
}
