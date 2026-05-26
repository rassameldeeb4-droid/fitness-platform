@extends('layouts.app')
@section('title', 'لوحة التحكم')
@section('content')
<div class="page-title">لوحة التحكم الرئيسية</div>
<div class="stat-grid">
    <div class="stat-card"><div class="stat-label"><i class="ti ti-users" style="color:#1D9E75"></i> المشتركون</div><div class="stat-value">{{ number_format($total_members) }}</div><div class="stat-sub">+23 هذا الشهر</div></div>
    <div class="stat-card"><div class="stat-label"><i class="ti ti-user-star" style="color:#534AB7"></i> المدربون</div><div class="stat-value">{{ $total_trainers }}</div><div class="stat-sub">متاحون الآن</div></div>
    <div class="stat-card"><div class="stat-label"><i class="ti ti-building" style="color:#185FA5"></i> الصالات</div><div class="stat-value">{{ $total_gyms }}</div><div class="stat-sub">فروع نشطة</div></div>
    <div class="stat-card"><div class="stat-label"><i class="ti ti-user-check" style="color:#0F6E56"></i> اشتراكات نشطة</div><div class="stat-value">{{ $active_subscriptions }}</div><div class="stat-sub">من الإجمالي</div></div>
    <div class="stat-card"><div class="stat-label"><i class="ti ti-currency-dollar" style="color:#854F0B"></i> الأرباح</div><div class="stat-value">{{ number_format($total_revenue) }}</div><div class="stat-sub">ريال هذا الشهر</div></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
    <div class="card">
        <div class="card-title">آخر الاشتراكات</div>
        <table>
            <thead><tr><th>المشترك</th><th>الباقة</th><th>الحالة</th></tr></thead>
            <tbody>
                @forelse($recent_subscriptions as $sub)
                <tr>
                    <td>{{ $sub->user->name ?? '—' }}</td>
                    <td>{{ $sub->package->name ?? '—' }}</td>
                    <td><span class="badge badge-green">{{ $sub->status === 'active' ? 'نشط' : ($sub->status === 'expired' ? 'منتهية' : 'ملغي') }}</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;color:var(--color-text-secondary)">لا توجد اشتراكات حديثة</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card">
        <div class="card-title">توزيع الباقات</div>
        @php
            $packages = [['name'=>'بريميوم سنوية','pct'=>62,'color'=>'#1D9E75'],['name'=>'شهرية','pct'=>23,'color'=>'#534AB7'],['name'=>'ربع سنوية','pct'=>10,'color'=>'#185FA5'],['name'=>'مجانية','pct'=>5,'color'=>'#888780']];
        @endphp
        @foreach($packages as $p)
        <div style="margin-bottom:12px">
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span>{{ $p['name'] }}</span><span style="color:var(--color-text-secondary)">{{ $p['pct'] }}%</span></div>
            <div class="progress-bar"><div class="progress-fill" style="width:{{ $p['pct'] }}%;background:{{ $p['color'] }}"></div></div>
        </div>
        @endforeach
    </div>
</div>
@endsection
