@extends('layouts.app')
@section('title', 'نظامي الغذائي')
@section('content')
<div class="page-title">نظامي الغذائي</div>
@if($plan)
<div class="card">
    <div class="card-title">{{ $plan->name ?? 'خطتي الغذائية' }}</div>
    <p style="font-size:13px;color:var(--color-text-secondary);margin-bottom:1rem">الهدف: {{ $plan->goal }} • {{ $plan->daily_calories }} سعرة يومياً</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:1rem">
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#534AB7">{{ $plan->daily_calories }}</div><div style="font-size:11px;color:var(--color-text-secondary)">سعرة</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#1D9E75">{{ $plan->protein }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">بروتين</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#185FA5">{{ $plan->carbs }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">كارب</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#854F0B">{{ $plan->fat }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">دهون</div></div>
    </div>
    @foreach($plan->meals as $meal)
    <div style="display:flex;gap:10px;align-items:center;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <div style="font-size:18px">{{ $meal->is_completed ? '✅' : '⏰' }}</div>
        <div style="flex:1"><div style="font-size:13px;font-weight:500">{{ $meal->name }}</div><div style="font-size:12px;color:var(--color-text-secondary)">{{ $meal->description }}</div></div>
        <span class="badge badge-green">{{ $meal->calories }} سعرة</span>
    </div>
    @endforeach
    @if($plan->notes)
    <div style="margin-top:1rem;font-size:12px;color:#534AB7;background:#EEEDFE;padding:10px;border-radius:var(--border-radius-md)">{{ $plan->notes }}</div>
    @endif
</div>
@else
<div class="card" style="text-align:center;padding:2rem">
    <i class="ti ti-salad" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem;display:block"></i>
    <p style="color:var(--color-text-secondary);font-size:14px">لم يتم إنشاء خطة غذائية لك بعد</p>
    <p style="color:var(--color-text-tertiary);font-size:12px;margin-top:4px">تواصل مع مدربك ليُعدّ لك خطة مخصصة</p>
</div>
@endif
@endsection
