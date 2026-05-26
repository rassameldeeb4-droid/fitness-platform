<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function index()
    {
        $thisMonth = Subscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        $thisYear = Subscription::whereYear('created_at', now()->year)->sum('amount');
        
        $newSubscriptions = Subscription::whereMonth('created_at', now()->month)
            ->where('status', 'active')->count();
        
        $renewals = Subscription::whereMonth('created_at', now()->month)
            ->where('auto_renew', true)->count();

        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = Subscription::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
        }

        return view('admin.revenue', compact('thisMonth', 'thisYear', 'newSubscriptions', 'renewals', 'monthlyRevenue'));
    }
}
