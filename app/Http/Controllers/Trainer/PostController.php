<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        TrainerPost::create([
            'trainer_id' => Auth::id(),
            'content' => $data['content'],
        ]);
        return back()->with('success', 'تم نشر التحديث بنجاح');
    }
}
