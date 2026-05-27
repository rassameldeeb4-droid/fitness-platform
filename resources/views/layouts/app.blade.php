<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1D9E75">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Fitness">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icons/icon.svg">
    <title>@yield('title', 'Fitness Platform') — Fitness</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.30.0/dist/tabler-icons.min.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif
    <style>
        :root {
            --color-background-primary: #fff;
            --color-background-secondary: #f8f7f4;
            --color-background-tertiary: #f5f4ef;
            --color-text-primary: #1a1a18;
            --color-text-secondary: #6b6a66;
            --color-text-tertiary: #888780;
            --color-border-secondary: #e3e1db;
            --color-border-tertiary: #eae8e2;
            --color-text-danger: #A32D2D;
            --color-safe-bottom: env(safe-area-inset-bottom, 0px);
            --font-sans: 'Tajawal', system-ui, sans-serif;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;
            --sidebar-width: 220px;
            --bottom-nav-height: 62px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:var(--font-sans);direction:rtl;background:var(--color-background-tertiary);color:var(--color-text-primary)}
        a{color:inherit;text-decoration:none}

        /* ===== SIDEBAR (desktop) ===== */
        .sidebar{width:var(--sidebar-width);background:var(--color-background-primary);border-left:0.5px solid var(--color-border-tertiary);padding:1rem 0;display:flex;flex-direction:column;position:fixed;right:0;top:0;height:100vh;z-index:100;overflow-y:auto}
        .logo{padding:0.5rem 1.25rem 1rem;border-bottom:0.5px solid var(--color-border-tertiary);margin-bottom:0.5rem}
        .logo-text{font-size:17px;font-weight:500;color:#1D9E75;display:flex;align-items:center;gap:8px}
        .logo-sub{font-size:11px;color:var(--color-text-secondary)}
        .nav-item{display:flex;align-items:center;gap:10px;padding:9px 1.25rem;font-size:13px;color:var(--color-text-secondary);cursor:pointer;border-right:3px solid transparent;text-decoration:none;transition:all .15s}
        .nav-item:hover{background:var(--color-background-secondary);color:var(--color-text-primary)}
        .nav-item.active-page{color:#1D9E75;border-right-color:#1D9E75;background:#E1F5EE}
        .nav-item i{font-size:17px;width:20px;text-align:center}
        .nav-sec{font-size:10px;color:var(--color-text-tertiary);padding:.75rem 1.25rem .2rem;text-transform:uppercase;letter-spacing:.5px}
        .nav-badge{min-width:18px;height:18px;border-radius:9px;background:#A32D2D;color:#fff;font-size:10px;font-weight:500;display:inline-flex;align-items:center;justify-content:center;padding:0 5px;margin-right:auto}
        .nav-badge-green{background:#1D9E75}
        .bottom-badge{position:absolute;top:-2px;left:auto;right:50%;margin-right:6px;min-width:16px;height:16px;border-radius:8px;background:#A32D2D;color:#fff;font-size:9px;font-weight:600;display:flex;align-items:center;justify-content:center;padding:0 4px;pointer-events:none}

        /* ===== MAIN CONTENT ===== */
        .main-content{margin-right:var(--sidebar-width);padding:1.5rem;min-height:100vh}

        /* ===== BOTTOM NAV (mobile only) ===== */
        .bottom-nav{display:none}
        .bottom-nav-overlay{display:none}

        /* ===== SHARED ===== */
        .page-title{font-size:20px;font-weight:500;color:var(--color-text-primary);margin-bottom:1.25rem}
        .card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:1.25rem;margin-bottom:1rem}
        .card-title{font-size:15px;font-weight:500;margin-bottom:1rem;color:var(--color-text-primary);display:flex;align-items:center;gap:8px}
        .badge{display:inline-flex;align-items:center;gap:3px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:500}
        .badge-green{background:#E1F5EE;color:#0F6E56}
        .badge-amber{background:#FAEEDA;color:#854F0B}
        .badge-red{background:#FCEBEB;color:#A32D2D}
        .badge-blue{background:#E6F1FB;color:#185FA5}
        .badge-purple{background:#EEEDFE;color:#3C3489}
        .btn{padding:8px 14px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;cursor:pointer;color:var(--color-text-primary);font-family:var(--font-sans);display:inline-flex;align-items:center;gap:6px;text-decoration:none}
        .btn:hover{background:var(--color-background-secondary)}
        .btn-primary{background:#1D9E75;color:#fff;border-color:#1D9E75}
        .btn-primary:hover{background:#0F6E56}
        .btn-ai{background:#534AB7;color:#fff;border-color:#534AB7}
        .btn-ai:hover{background:#3C3489}
        .btn-wa{background:#25D366;color:#fff;border-color:#25D366}
        .btn-wa:hover{background:#1ebe5d}
        .btn-sm{padding:5px 10px;font-size:12px}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th{text-align:right;padding:8px 12px;font-size:12px;color:var(--color-text-secondary);font-weight:400;border-bottom:0.5px solid var(--color-border-tertiary)}
        td{padding:9px 12px;border-bottom:0.5px solid var(--color-border-tertiary);color:var(--color-text-primary)}
        tr:last-child td{border-bottom:none}
        .avatar{border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:500;flex-shrink:0}
        .progress-bar{height:6px;background:var(--color-background-secondary);border-radius:3px;overflow:hidden;margin-top:4px}
        .progress-fill{height:100%;background:#1D9E75;border-radius:3px;transition:width .3s}
        .input-grp{display:flex;flex-direction:column;gap:4px}
        .input-grp label{font-size:12px;color:var(--color-text-secondary)}
        .input-grp input,.input-grp select,.input-grp textarea{padding:8px 10px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;color:var(--color-text-primary);font-family:var(--font-sans);outline:none}
        .stat-card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:1rem 1.25rem}
        .stat-label{font-size:12px;color:var(--color-text-secondary);margin-bottom:6px}
        .stat-value{font-size:24px;font-weight:500;color:var(--color-text-primary)}
        .stat-sub{font-size:11px;color:var(--color-text-tertiary);margin-top:2px}
        .stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:1.5rem}
        .spinner{display:inline-block;width:18px;height:18px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle}
        @keyframes spin{to{transform:rotate(360deg)}}
        .flex-row{display:flex;gap:8px;align-items:center}
        .cat-pill{padding:4px 12px;border-radius:20px;font-size:12px;border:0.5px solid var(--color-border-secondary);cursor:pointer;color:var(--color-text-secondary);background:var(--color-background-primary)}
        .cat-pill.active{background:#1D9E75;color:#fff;border-color:#1D9E75}
        .alert{background:#E1F5EE;border-radius:var(--border-radius-md);padding:10px 14px;font-size:13px;color:#0F6E56;margin-bottom:1rem;display:flex;align-items:center;gap:8px}
        .table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;margin-bottom:1rem}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .grid-2-1{display:grid;grid-template-columns:2fr 1fr;gap:12px}

        @media(max-width:900px){
            .grid-2,.grid-2-1{grid-template-columns:1fr}
        }

        /* ===== MOBILE ===== */
        @media(max-width:768px){
            .sidebar{display:none}
            .main-content{margin-right:0;padding:1rem 0.75rem 5rem;padding-bottom:calc(var(--bottom-nav-height) + 0.75rem)}
            .bottom-nav{display:flex;position:fixed;bottom:0;right:0;left:0;height:calc(var(--bottom-nav-height) + var(--color-safe-bottom));padding-bottom:var(--color-safe-bottom);background:var(--color-background-primary);border-top:0.5px solid var(--color-border-tertiary);z-index:100;justify-content:space-around;align-items:center;box-shadow:0 -2px 12px rgba(0,0,0,0.06)}
            .bottom-nav a,.bottom-nav .more-btn{display:flex;flex-direction:column;align-items:center;gap:2px;padding:4px 6px;font-size:9px;color:var(--color-text-tertiary);text-decoration:none;cursor:pointer;position:relative;border:none;background:none;font-family:var(--font-sans)}
            .bottom-nav a i,.bottom-nav .more-btn i{font-size:20px;transition:color .15s}
            .bottom-nav a.active-page,.bottom-nav a.active-page i{color:#1D9E75}
            .bottom-nav .more-btn{color:var(--color-text-tertiary)}
            .bottom-nav .more-btn.active{color:#1D9E75}
            .bottom-nav-overlay{display:none;position:fixed;inset:0;z-index:99;background:rgba(0,0,0,0.3)}
            .bottom-nav-overlay.show{display:block}
            .more-menu{display:none;position:fixed;bottom:calc(var(--bottom-nav-height) + var(--color-safe-bottom));right:0;left:0;z-index:101;background:var(--color-background-primary);border-radius:16px 16px 0 0;padding:1rem;box-shadow:0 -4px 20px rgba(0,0,0,0.1);max-height:60vh;overflow-y:auto}
            .more-menu.show{display:block}
            .more-menu .more-item{display:flex;align-items:center;gap:10px;padding:12px 10px;font-size:13px;color:var(--color-text-primary);text-decoration:none;border-bottom:0.5px solid var(--color-border-tertiary)}
            .more-menu .more-item:last-child{border-bottom:none}
            .more-menu .more-item i{font-size:18px;width:22px;text-align:center;color:var(--color-text-secondary)}
            .more-menu .more-title{font-size:14px;font-weight:500;padding:8px 10px 12px;color:var(--color-text-primary);display:flex;align-items:center;justify-content:space-between}
            .more-menu .more-title .close-more{font-size:20px;cursor:pointer;color:var(--color-text-tertiary)}
            .page-title{font-size:18px}
            .stat-grid{grid-template-columns:repeat(2,1fr);gap:8px}
            .stat-value{font-size:20px}
            .card{padding:1rem}
            .table-wrap{margin-left:-0.75rem;margin-right:-0.75rem}
            .table-wrap table{font-size:12px}
            .table-wrap th,.table-wrap td{padding:6px 8px;white-space:nowrap}
            .btn{padding:6px 12px;font-size:12px}
        }
        @media(max-width:480px){
            .stat-grid{grid-template-columns:1fr 1fr;gap:6px}
            .stat-card{padding:.75rem}
            .stat-value{font-size:18px}
            .card{padding:.75rem}
        }
    </style>
    @stack('styles')
</head>
<body>
@php
$role = auth()->user()->role ?? 'guest';
$route = request()->route()?->getName() ?? '';
$r = fn($name) => $route === $name || str_starts_with($route, $name);
$notifCount = 0;
$traineeCount = 0;
$memberCount = 0;
$subCount = 0;
$cartCount = count(session()->get('cart', []));
if ($role === 'member') {
    $notifCount = \App\Models\TimelineEvent::where('related_user_id', auth()->id())->where('is_read', false)->count();
}
if ($role === 'trainer') {
    $traineeCount = \App\Models\User::where('role', 'member')
        ->whereHas('memberProfile', fn($q) => $q->where('trainer_id', auth()->id()))->count();
}
if ($role === 'doctor') {
    $traineeCount = \App\Models\User::where('role', 'member')
        ->whereHas('memberProfile', fn($q) => $q->where('doctor_id', auth()->id()))->count();
}
if ($role === 'admin' || $role === 'super_admin') {
    $memberCount = \App\Models\User::where('role', 'member')->count();
    $subCount = \App\Models\Subscription::where('status', 'active')->count();
}
@endphp

{{-- ===== SIDEBAR (desktop) ===== --}}
<div class="sidebar">
    <div class="logo">
        <a href="{{ route('dashboard') }}" class="logo-text"><i class="ti ti-barbell"></i> Fitness</a>
        <div class="logo-sub">
            @auth
                @if($role === 'admin') لوحة المسؤول
                @elseif($role === 'super_admin') الإدارة العليا
                @elseif($role === 'trainer') لوحة المدرب
                @elseif($role === 'doctor') عيادة الطبيب
                @else لوحة المتدرب
                @endif
            @endauth
        </div>
    </div>
    @auth
        @if($role === 'admin')
            <div class="nav-sec">الرئيسية</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ $r('admin.dashboard') ? 'active-page' : '' }}"><i class="ti ti-layout-dashboard"></i> الرئيسية</a>
            <a href="{{ route('admin.members') }}" class="nav-item {{ $r('admin.members') ? 'active-page' : '' }}"><i class="ti ti-users"></i> المشتركون @if($memberCount)<span class="nav-badge nav-badge-green">{{ $memberCount }}</span>@endif</a>
            <a href="{{ route('admin.trainers') }}" class="nav-item {{ $r('admin.trainers') ? 'active-page' : '' }}"><i class="ti ti-user-star"></i> المدربون</a>
            <a href="{{ route('admin.doctors') }}" class="nav-item {{ $r('admin.doctors') ? 'active-page' : '' }}"><i class="ti ti-stethoscope"></i> الأطباء</a>
            <a href="{{ route('admin.gyms') }}" class="nav-item {{ $r('admin.gyms') ? 'active-page' : '' }}"><i class="ti ti-building"></i> الصالات</a>
            <a href="{{ route('admin.packages') }}" class="nav-item {{ $r('admin.packages') ? 'active-page' : '' }}"><i class="ti ti-credit-card"></i> الباقات @if($subCount)<span class="nav-badge nav-badge-green">{{ $subCount }}</span>@endif</a>
            <a href="{{ route('admin.revenue') }}" class="nav-item {{ $r('admin.revenue') ? 'active-page' : '' }}"><i class="ti ti-chart-bar"></i> الأرباح</a>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ $r('admin.settings') ? 'active-page' : '' }}"><i class="ti ti-settings"></i> الإعدادات</a>
        @elseif($role === 'super_admin')
            <div class="nav-sec">الرئيسية</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ $r('admin.dashboard') ? 'active-page' : '' }}"><i class="ti ti-layout-dashboard"></i> الرئيسية</a>
            <a href="{{ route('admin.members') }}" class="nav-item {{ $r('admin.members') ? 'active-page' : '' }}"><i class="ti ti-users"></i> المشتركون @if($memberCount)<span class="nav-badge nav-badge-green">{{ $memberCount }}</span>@endif</a>
            <a href="{{ route('admin.trainers') }}" class="nav-item {{ $r('admin.trainers') ? 'active-page' : '' }}"><i class="ti ti-user-star"></i> المدربون</a>
            <a href="{{ route('admin.doctors') }}" class="nav-item {{ $r('admin.doctors') ? 'active-page' : '' }}"><i class="ti ti-stethoscope"></i> الأطباء</a>
            <a href="{{ route('admin.gyms') }}" class="nav-item {{ $r('admin.gyms') ? 'active-page' : '' }}"><i class="ti ti-building"></i> الصالات</a>
            <a href="{{ route('admin.packages') }}" class="nav-item {{ $r('admin.packages') ? 'active-page' : '' }}"><i class="ti ti-credit-card"></i> الباقات @if($subCount)<span class="nav-badge nav-badge-green">{{ $subCount }}</span>@endif</a>
            <a href="{{ route('admin.revenue') }}" class="nav-item {{ $r('admin.revenue') ? 'active-page' : '' }}"><i class="ti ti-chart-bar"></i> الأرباح</a>
            <div class="nav-sec">المتجر</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ $r('admin.products') ? 'active-page' : '' }}"><i class="ti ti-package"></i> المنتجات</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ $r('admin.orders') ? 'active-page' : '' }}"><i class="ti ti-shopping-cart"></i> الطلبات</a>
            <div class="nav-sec">التمارين</div>
            <a href="{{ route('admin.exercises.index') }}" class="nav-item {{ $r('admin.exercises') ? 'active-page' : '' }}"><i class="ti ti-barbell"></i> التمارين</a>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ $r('admin.settings') ? 'active-page' : '' }}"><i class="ti ti-settings"></i> الإعدادات</a>
        @elseif($role === 'trainer')
            <div class="nav-sec">الرئيسية</div>
            <a href="{{ route('trainer.dashboard') }}" class="nav-item {{ $r('trainer.dashboard') ? 'active-page' : '' }}"><i class="ti ti-timeline"></i> التايم لاين</a>
            <a href="{{ route('trainer.trainees') }}" class="nav-item {{ $r('trainer.trainees') ? 'active-page' : '' }}"><i class="ti ti-users"></i> متدربيّ @if($traineeCount)<span class="nav-badge nav-badge-green">{{ $traineeCount }}</span>@endif</a>
            <div class="nav-sec">التدريب والتغذية</div>
            <a href="{{ route('trainer.progress') }}" class="nav-item"><i class="ti ti-chart-line"></i> متابعة التقدم</a>
            <div class="nav-sec">التواصل</div>
            <a href="{{ route('chat.index') }}" class="nav-item {{ $r('chat') ? 'active-page' : '' }}"><i class="ti ti-message-circle"></i> المحادثات</a>
            <a href="{{ route('exercises') }}" class="nav-item"><i class="ti ti-barbell"></i> مكتبة التمارين</a>
            <a href="{{ route('store') }}" class="nav-item"><i class="ti ti-shopping-bag"></i> المتجر</a>
        @elseif($role === 'doctor')
            <div class="nav-sec">الرئيسية</div>
            <a href="{{ route('doctor.dashboard') }}" class="nav-item {{ $r('doctor.dashboard') ? 'active-page' : '' }}"><i class="ti ti-stethoscope"></i> عيادتي</a>
            <a href="{{ route('doctor.patients') }}" class="nav-item {{ $r('doctor.patients') ? 'active-page' : '' }}"><i class="ti ti-users"></i> مرضاي @if($traineeCount)<span class="nav-badge nav-badge-green">{{ $traineeCount }}</span>@endif</a>
            <div class="nav-sec">الخطط</div>
            <a href="{{ route('chat.index') }}" class="nav-item {{ $r('chat') ? 'active-page' : '' }}"><i class="ti ti-message-circle"></i> المحادثات</a>
            <a href="{{ route('exercises') }}" class="nav-item"><i class="ti ti-barbell"></i> مكتبة التمارين</a>
            <a href="{{ route('store') }}" class="nav-item"><i class="ti ti-shopping-bag"></i> المتجر</a>
        @else
            <div class="nav-sec">الرئيسية</div>
            <a href="{{ route('member.dashboard') }}" class="nav-item {{ $r('member.dashboard') ? 'active-page' : '' }}"><i class="ti ti-layout-dashboard"></i> لوحتي</a>
            <a href="{{ route('member.nutrition') }}" class="nav-item {{ $r('member.nutrition') ? 'active-page' : '' }}"><i class="ti ti-salad"></i> نظامي الغذائي</a>
            <a href="{{ route('member.workouts') }}" class="nav-item {{ $r('member.workouts') ? 'active-page' : '' }}"><i class="ti ti-barbell"></i> جدول التمارين</a>
            <a href="{{ route('member.progress') }}" class="nav-item {{ $r('member.progress') ? 'active-page' : '' }}"><i class="ti ti-chart-line"></i> تقدمي</a>
            <a href="{{ route('member.food-analyzer') }}" class="nav-item {{ $r('member.food-analyzer') ? 'active-page' : '' }}"><i class="ti ti-search"></i> محلل الطعام</a>
            <a href="{{ route('member.notifications') }}" class="nav-item {{ $r('member.notifications') ? 'active-page' : '' }}"><i class="ti ti-bell"></i> تنبيهاتي @if($notifCount)<span class="nav-badge">{{ $notifCount }}</span>@endif</a>
            <div class="nav-sec">التواصل</div>
            <a href="{{ route('chat.index') }}" class="nav-item {{ $r('chat') ? 'active-page' : '' }}"><i class="ti ti-message-circle"></i> المحادثات</a>
            <div class="nav-sec">المتجر</div>
            <a href="{{ route('store') }}" class="nav-item {{ $r('store') ? 'active-page' : '' }}"><i class="ti ti-shopping-bag"></i> المتجر</a>
            <a href="{{ route('store.orders') }}" class="nav-item {{ $r('store.orders') ? 'active-page' : '' }}"><i class="ti ti-receipt"></i> طلباتي</a>
        @endif
    @endauth
    <div style="flex:1"></div>
    <div style="padding:1rem 1.25rem;border-top:0.5px solid var(--color-border-tertiary)">
        <div style="display:flex;align-items:center;gap:8px">
            <div class="avatar" style="width:32px;height:32px;background:#E1F5EE;color:#0F6E56;font-size:13px">{{ substr(auth()->user()->name ?? 'م', 0, 1) }}</div>
            <div style="flex:1">
                <div style="font-size:12px;font-weight:500">{{ auth()->user()->name ?? 'مستخدم' }}</div>
                <div style="font-size:10px;color:#1D9E75">● متصل</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--color-text-tertiary);cursor:pointer;padding:4px"><i class="ti ti-logout" style="font-size:16px"></i></button>
            </form>
        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="main-content">
    @if(session('success'))
        <div class="alert"><i class="ti ti-check-circle"></i> {{ session('success') }}</div>
    @endif
    @yield('content')
</div>

{{-- ===== BOTTOM NAV (mobile) ===== --}}
@auth
@php
$bottomItems = [];
if ($role === 'admin') {
    $bottomItems = [
        ['route' => 'admin.dashboard', 'icon' => 'ti ti-layout-dashboard', 'label' => 'الرئيسية'],
        ['route' => 'admin.members', 'icon' => 'ti ti-users', 'label' => 'المشتركون'],
        ['route' => 'admin.trainers', 'icon' => 'ti ti-user-star', 'label' => 'المدربون'],
        ['route' => 'admin.packages', 'icon' => 'ti ti-credit-card', 'label' => 'الباقات'],
    ];
    $moreItems = [
        ['route' => 'admin.doctors', 'icon' => 'ti ti-stethoscope', 'label' => 'الأطباء'],
        ['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],
        ['route' => 'admin.revenue', 'icon' => 'ti ti-chart-bar', 'label' => 'الأرباح'],
        ['route' => 'admin.settings', 'icon' => 'ti ti-settings', 'label' => 'الإعدادات'],
    ];
} elseif ($role === 'super_admin') {
    $bottomItems = [
        ['route' => 'admin.dashboard', 'icon' => 'ti ti-layout-dashboard', 'label' => 'الرئيسية'],
        ['route' => 'admin.members', 'icon' => 'ti ti-users', 'label' => 'المشتركون'],
        ['route' => 'admin.products.index', 'icon' => 'ti ti-package', 'label' => 'المنتجات'],
        ['route' => 'admin.orders.index', 'icon' => 'ti ti-shopping-cart', 'label' => 'الطلبات'],
    ];
    $moreItems = [
        ['route' => 'admin.trainers', 'icon' => 'ti ti-user-star', 'label' => 'المدربون'],
        ['route' => 'admin.doctors', 'icon' => 'ti ti-stethoscope', 'label' => 'الأطباء'],
        ['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],
        ['route' => 'admin.packages', 'icon' => 'ti ti-credit-card', 'label' => 'الباقات'],
        ['route' => 'admin.revenue', 'icon' => 'ti ti-chart-bar', 'label' => 'الأرباح'],
        ['route' => 'admin.exercises.index', 'icon' => 'ti ti-barbell', 'label' => 'التمارين'],
        ['route' => 'admin.settings', 'icon' => 'ti ti-settings', 'label' => 'الإعدادات'],
    ];
} elseif ($role === 'trainer') {
    $bottomItems = [
        ['route' => 'trainer.dashboard', 'icon' => 'ti ti-timeline', 'label' => 'الرئيسية'],
        ['route' => 'trainer.trainees', 'icon' => 'ti ti-users', 'label' => 'متدربيّ'],
        ['route' => 'chat.index', 'icon' => 'ti ti-message-circle', 'label' => 'المحادثات'],
        ['route' => 'exercises', 'icon' => 'ti ti-barbell', 'label' => 'التمارين'],
    ];
    $moreItems = [
        ['route' => 'trainer.progress', 'icon' => 'ti ti-chart-line', 'label' => 'متابعة التقدم'],
        ['route' => 'store', 'icon' => 'ti ti-shopping-bag', 'label' => 'المتجر'],
    ];
} elseif ($role === 'doctor') {
    $bottomItems = [
        ['route' => 'doctor.dashboard', 'icon' => 'ti ti-stethoscope', 'label' => 'عيادتي'],
        ['route' => 'doctor.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],
        ['route' => 'doctor.patients', 'icon' => 'ti ti-users', 'label' => 'مرضاي'],
        ['route' => 'chat.index', 'icon' => 'ti ti-message-circle', 'label' => 'المحادثات'],
        ['route' => 'exercises', 'icon' => 'ti ti-barbell', 'label' => 'التمارين'],
    ];
    $moreItems = [
        ['route' => 'store', 'icon' => 'ti ti-shopping-bag', 'label' => 'المتجر'],
    ];
} else {
    $bottomItems = [
        ['route' => 'member.dashboard', 'icon' => 'ti ti-layout-dashboard', 'label' => 'لوحتي'],
        ['route' => 'member.workouts', 'icon' => 'ti ti-barbell', 'label' => 'تماريني'],
        ['route' => 'member.nutrition', 'icon' => 'ti ti-salad', 'label' => 'غذائي'],
        ['route' => 'member.progress', 'icon' => 'ti ti-chart-line', 'label' => 'تقدمي'],
    ];
    $moreItems = [
        ['route' => 'member.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],
        ['route' => 'member.food-analyzer', 'icon' => 'ti ti-search', 'label' => 'محلل الطعام'],
        ['route' => 'chat.index', 'icon' => 'ti ti-message-circle', 'label' => 'المحادثات'],
        ['route' => 'member.notifications', 'icon' => 'ti ti-bell', 'label' => 'التنبيهات'],
        ['route' => 'cart.index', 'icon' => 'ti ti-shopping-cart', 'label' => 'السلة'],
        ['route' => 'store.orders', 'icon' => 'ti ti-receipt', 'label' => 'طلباتي'],
    ];
}
$isMoreActive = collect($moreItems)->contains(fn($i) => $r($i['route']));
@endphp
<div class="bottom-nav-overlay" id="moreOverlay" onclick="toggleMore()"></div>
<div class="more-menu" id="moreMenu">
    <div class="more-title">المزيد <span class="close-more" onclick="toggleMore()"><i class="ti ti-x"></i></span></div>
    @foreach($moreItems as $item)
    <a href="{{ route($item['route']) }}" class="more-item {{ $r($item['route']) ? 'active-page' : '' }}">
        <i class="{{ $item['icon'] }}"></i>
        {{ $item['label'] }}
        @if($item['route'] === 'member.notifications' && $notifCount)
            <span class="nav-badge" style="margin-right:auto">{{ $notifCount }}</span>
        @endif
    </a>
    @endforeach
    <div style="padding:12px 10px 4px;border-top:0.5px solid var(--color-border-tertiary);margin-top:8px">
        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--color-text-secondary)">
            <div class="avatar" style="width:28px;height:28px;background:#E1F5EE;color:#0F6E56;font-size:11px">{{ substr(auth()->user()->name ?? 'م', 0, 1) }}</div>
            <span style="flex:1">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--color-text-tertiary);cursor:pointer;padding:4px"><i class="ti ti-logout" style="font-size:16px"></i></button>
            </form>
        </div>
    </div>
</div>
<div class="bottom-nav">
    @foreach($bottomItems as $item)
    <a href="{{ route($item['route']) }}" class="{{ $r($item['route']) ? 'active-page' : '' }}" style="position:relative">
        <i class="{{ $item['icon'] }}"></i>
        @if($role === 'admin' && $item['route'] === 'admin.members' && $memberCount)
            <span class="bottom-badge">{{ $memberCount }}</span>
        @elseif($role === 'super_admin' && $item['route'] === 'admin.members' && $memberCount)
            <span class="bottom-badge">{{ $memberCount }}</span>
        @elseif($role === 'trainer' && $item['route'] === 'trainer.trainees' && $traineeCount)
            <span class="bottom-badge">{{ $traineeCount }}</span>
        @elseif($role === 'doctor' && $item['route'] === 'doctor.patients' && $traineeCount)
            <span class="bottom-badge">{{ $traineeCount }}</span>
        @elseif($role === 'member' && $item['route'] === 'cart.index' && $cartCount)
            <span class="bottom-badge" style="background:#1D9E75">{{ $cartCount }}</span>
        @endif
        <span>{{ $item['label'] }}</span>
    </a>
    @endforeach
    <button class="more-btn {{ $isMoreActive ? 'active' : '' }}" onclick="toggleMore()" style="position:relative">
        <i class="ti ti-dots-grid"></i>
        @if(($role === 'member' && $notifCount) || ($role === 'admin' && $subCount) || ($role === 'super_admin' && $subCount))
            <span class="bottom-badge" style="background:#1D9E75">{{ $subCount }}</span>
        @endif
        <span>المزيد</span>
    </button>
</div>
@endauth

<script>
function toggleMore() {
    document.getElementById('moreMenu').classList.toggle('show');
    document.getElementById('moreOverlay').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    var menu = document.getElementById('moreMenu');
    var overlay = document.getElementById('moreOverlay');
    if (window.innerWidth <= 768 && menu.classList.contains('show') && !menu.contains(e.target) && !e.target.closest('.more-btn')) {
        menu.classList.remove('show');
        overlay.classList.remove('show');
    }
});
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').catch(function(e) {
            console.log('SW failed:', e);
        });
    });
}
</script>
@stack('scripts')
</body>
</html>