<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => AppSetting::getValue('site_name', 'Fitness Platform'),
            'site_email' => AppSetting::getValue('site_email', 'admin@fitness.com'),
            'notif_expiry_7' => AppSetting::getValue('notif_expiry_7', '1'),
            'notif_auto_renew' => AppSetting::getValue('notif_auto_renew', '1'),
            'notif_late_payment' => AppSetting::getValue('notif_late_payment', '0'),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'notif_expiry_7' => 'boolean',
            'notif_auto_renew' => 'boolean',
            'notif_late_payment' => 'boolean',
        ]);

        foreach ($data as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'تم حفظ الإعدادات');
    }
}
