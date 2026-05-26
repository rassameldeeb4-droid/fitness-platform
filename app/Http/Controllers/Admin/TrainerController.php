<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = User::where('role', 'trainer')->with('trainerProfile')->paginate(15);
        return view('admin.trainers', compact('trainers'));
    }

    public function show($id)
    {
        $trainer = User::where('role', 'trainer')->with('trainerProfile', 'trainerReviews')->findOrFail($id);
        return view('admin.trainer-show', compact('trainer'));
    }
}
