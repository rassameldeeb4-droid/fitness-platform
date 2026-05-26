<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gym;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_members' => User::where('role', 'member')->count(),
            'total_trainers' => User::where('role', 'trainer')->count(),
            'total_gyms' => Gym::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'recent_subscriptions' => Subscription::with('user', 'package')->latest()->take(5)->get(),
            'monthly_revenue' => [42, 58, 51, 67, 73, 81, 76, 84, 79, 88, 84, 0],
        ];
        return view('admin.dashboard', $data);
    }
}
