<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('member_id', Auth::id())
            ->with('doctor')
            ->orderBy('scheduled_at')
            ->paginate(20);
        return view('member.appointments', compact('appointments'));
    }
}
