<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gym;
use App\Models\Subscription;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $newMembersThisMonth = User::where('role', 'member')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $packageSubs = Subscription::select('package_id', DB::raw('count(*) as total'))
            ->groupBy('package_id')
            ->with('package')
            ->get();

        $totalSubs = $packageSubs->sum('total') ?: 1;
        $packageColors = ['#1D9E75', '#534AB7', '#185FA5', '#854F0B', '#A32D2D', '#888780'];
        $packageDistribution = $packageSubs->map(function ($item, $i) use ($totalSubs, $packageColors) {
            return [
                'name' => $item->package->name ?? 'بدون باقة',
                'pct' => round(($item->total / $totalSubs) * 100),
                'color' => $packageColors[$i % count($packageColors)],
                'count' => $item->total,
            ];
        });

        $data = [
            'total_members' => User::where('role', 'member')->count(),
            'total_trainers' => User::where('role', 'trainer')->count(),
            'total_gyms' => Gym::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount'),
            'recent_subscriptions' => Subscription::with('user', 'package')->latest()->take(5)->get(),
            'new_members_this_month' => $newMembersThisMonth,
            'available_trainers' => User::where('role', 'trainer')->count(),
            'package_distribution' => $packageDistribution,
        ];
        return view('admin.dashboard', $data);
    }
}
