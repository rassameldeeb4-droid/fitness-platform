<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        // Save settings logic
        return redirect()->route('admin.settings')->with('success', 'تم حفظ الإعدادات');
    }
}
