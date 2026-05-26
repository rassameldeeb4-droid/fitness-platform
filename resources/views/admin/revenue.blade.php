@extends('layouts.app')
@section('title', 'الأرباح')
@section('content')
<div class="page-title">تقارير الأرباح</div>
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card"><div class="stat-label">هذا الشهر</div><div class="stat-value" style="color:#1D9E75">{{ number_format($thisMonth) }}</div><div class="stat-sub">ريال</div></div>
    <div class="stat-card"><div class="stat-label">هذا العام</div><div class="stat-value">{{ number_format($thisYear) }}</div><div class="stat-sub">ريال</div></div>
    <div class="stat-card"><div class="stat-label">اشتراكات جديدة</div><div class="stat-value">{{ $newSubscriptions }}</div><div class="stat-sub">هذا الشهر</div></div>
    <div class="stat-card"><div class="stat-label">تجديدات</div><div class="stat-value">{{ $renewals }}</div><div class="stat-sub">هذا الشهر</div></div>
</div>
<div class="card">
    <div class="card-title">الإيرادات الشهرية ({{ now()->year }})</div>
    <div style="display:flex;align-items:flex-end;gap:6px;height:120px;padding-top:1rem">
        @php $months = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']; @endphp
        @foreach($monthlyRevenue as $i => $rev)
        @php $height = $rev > 0 ? min(round(($rev / 100000) * 100), 100) : 4; @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
            <div style="flex:1;background:{{ $rev > 0 ? '#1D9E75' : 'var(--color-background-secondary)' }};border-radius:4px 4px 0 0;width:100%;min-height:4px;height:{{ $height }}%"></div>
            <div style="font-size:10px;color:var(--color-text-tertiary)">{{ substr($months[$i], 0, 3) }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
