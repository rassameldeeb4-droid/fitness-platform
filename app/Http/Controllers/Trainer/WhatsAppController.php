<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerWhatsAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppController extends Controller
{
    public function index()
    {
        $config = TrainerWhatsAppConfig::where('trainer_id', Auth::id())->first();
        return view('trainer.whatsapp', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'server_url' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'notify_nutrition' => 'boolean',
            'notify_workout' => 'boolean',
            'notify_progress' => 'boolean',
        ]);

        $config = TrainerWhatsAppConfig::updateOrCreate(
            ['trainer_id' => Auth::id()],
            [
                'server_url' => $data['server_url'] ?? null,
                'api_key' => $data['api_key'] ?? null,
                'phone_number' => $data['phone_number'] ?? null,
                'notify_nutrition' => $request->boolean('notify_nutrition'),
                'notify_workout' => $request->boolean('notify_workout'),
                'notify_progress' => $request->boolean('notify_progress'),
            ]
        );

        $msg = $config->wasRecentlyCreated ? 'تم حفظ الإعدادات' : 'تم تحديث الإعدادات';

        return redirect()->route('trainer.whatsapp')->with('success', $msg);
    }

    public function test(Request $request)
    {
        $config = TrainerWhatsAppConfig::where('trainer_id', Auth::id())->first();
        if (!$config || !$config->is_connected) {
            return back()->withErrors('WhatsApp غير متصل');
        }
        $result = $config->sendMessage($config->phone_number, '🔔 رسالة اختبارية من FitCore ✅ منصة');

        if ($result['success']) {
            return back()->with('success', 'تم إرسال رسالة الاختبار بنجاح');
        }
        return back()->withErrors('فشل الإرسال: ' . ($result['error'] ?? 'خطأ غير معروف'));
    }

    public function sendBulk(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $config = TrainerWhatsAppConfig::where('trainer_id', Auth::id())->first();
        if (!$config || !$config->is_connected) {
            return response()->json(['success' => false, 'error' => 'WhatsApp غير متصل']);
        }

        $members = \App\Models\User::whereIn('id', $data['member_ids'])->get();
        $sent = 0; $failed = 0;

        foreach ($members as $member) {
            $phone = $member->phone;
            if (!$phone) { $failed++; continue; }
            $msg = str_replace(
                ['{name}', '{trainer_name}'],
                [$member->name, Auth::user()->name],
                $data['message']
            );
            $result = $config->sendMessage($phone, $msg);
            if ($result['success']) { $sent++; } else { $failed++; }
        }

        return response()->json([
            'success' => true,
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }
}
