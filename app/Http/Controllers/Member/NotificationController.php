<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $settings = NotificationSetting::firstOrCreate(['user_id' => Auth::id()]);
        return view('member.notifications', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'workout_reminder' => 'boolean',
            'meal_reminder' => 'boolean',
            'water_reminder' => 'boolean',
            'sleep_reminder' => 'boolean',
            'measurement_reminder' => 'boolean',
            'supplement_reminder' => 'boolean',
            'whatsapp_enabled' => 'boolean',
            'whatsapp_phone' => 'nullable|string',
        ]);
        NotificationSetting::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );
        return back()->with('success', 'تم حفظ إعدادات التنبيهات');
    }
}
