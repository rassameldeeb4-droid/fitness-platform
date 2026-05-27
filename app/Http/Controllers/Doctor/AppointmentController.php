<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())
            ->with('member')
            ->orderBy('scheduled_at')
            ->paginate(20);

        $upcoming = Appointment::where('doctor_id', Auth::id())
            ->where('scheduled_at', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $today = Appointment::where('doctor_id', Auth::id())
            ->whereDate('scheduled_at', today())
            ->count();

        return view('doctor.appointments', compact('appointments', 'upcoming', 'today'));
    }

    public function create()
    {
        $patients = User::where('role', 'member')
            ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', Auth::id()))
            ->get();

        return view('doctor.appointment-create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:120',
            'notes' => 'nullable|string',
        ]);

        // Check for conflicts
        $conflict = Appointment::where('doctor_id', Auth::id())
            ->where('scheduled_at', '<', $data['scheduled_at'])
            ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE) > ?', [$data['scheduled_at']])
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($conflict) {
            return back()->withErrors(['scheduled_at' => 'يوجد موعد آخر في هذا الوقت'])->withInput();
        }

        Appointment::create(array_merge($data, ['doctor_id' => Auth::id()]));

        return redirect()->route('doctor.appointments')->with('success', 'تم إضافة الموعد');
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) abort(403);
        $appointment->load('member.memberProfile');
        return view('doctor.appointment-show', compact('appointment'));
    }

    public function complete(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) abort(403);
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'تم إكمال الجلسة');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) abort(403);

        $request->validate(['reason' => 'nullable|string']);
        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
        ]);

        return back()->with('success', 'تم إلغاء الموعد');
    }
}
