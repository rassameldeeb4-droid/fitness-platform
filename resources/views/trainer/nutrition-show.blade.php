@extends('layouts.app')
@section('title', 'الخطة الغذائية')
@section('content')
<div class="page-title">الخطة الغذائية لـ {{ $plan->member->name }}</div>
<div class="card">
    <div class="card-title">{{ $plan->goal }} • {{ $plan->daily_calories }} سعرة/يوم</div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:1rem">
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#534AB7">{{ $plan->daily_calories }}</div><div style="font-size:11px;color:var(--color-text-secondary)">سعرة</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#1D9E75">{{ $plan->protein }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">بروتين</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#185FA5">{{ $plan->carbs }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">كارب</div></div>
        <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#854F0B">{{ $plan->fat }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">دهون</div></div>
    </div>
    @foreach($plan->meals as $meal)
    <div style="display:flex;gap:10px;align-items:center;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <div style="font-size:18px">🍽️</div>
        <div style="flex:1"><div style="font-size:13px;font-weight:500">{{ $meal->name }} <span style="font-size:11px;color:var(--color-text-tertiary)">({{ $meal->time }})</span></div><div style="font-size:12px;color:var(--color-text-secondary)">{{ $meal->description }}</div></div>
        <span class="badge badge-green">{{ $meal->calories }} سعرة</span>
    </div>
    @endforeach
</div>
<a href="{{ route('trainer.nutrition.create', $plan->member_id) }}" class="btn btn-primary"><i class="ti ti-plus"></i> خطة جديدة</a>
@endsection
