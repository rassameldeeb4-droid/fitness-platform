@extends('layouts.app')
@section('title', 'النظام الغذائي')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">النظام الغذائي لـ {{ $plan->member->name }}</div>
    <a href="{{ route('doctor.patients.show', $plan->member_id) }}" class="btn">عودة</a>
</div>
<div class="stat-grid">
    <div class="stat-card"><div class="stat-label">الهدف</div><div class="stat-value" style="font-size:16px">{{ $plan->goal }}</div></div>
    <div class="stat-card"><div class="stat-label">السعرات</div><div class="stat-value" style="color:#534AB7">{{ $plan->daily_calories }} <span style="font-size:14px">سعرة</span></div></div>
    <div class="stat-card"><div class="stat-label">بروتين</div><div class="stat-value" style="font-size:20px;color:#1D9E75">{{ $plan->protein }} غ</div></div>
    <div class="stat-card"><div class="stat-label">كارب</div><div class="stat-value" style="font-size:20px;color:#185FA5">{{ $plan->carbs }} غ</div></div>
    <div class="stat-card"><div class="stat-label">دهون</div><div class="stat-value" style="font-size:20px;color:#854F0B">{{ $plan->fat }} غ</div></div>
</div>
@if($plan->meals->count())
<div class="card"><div class="card-title"><i class="ti ti-salad"></i> الوجبات</div>
@foreach($plan->meals as $meal)
<div style="border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:10px 12px;margin-bottom:8px">
    <div style="display:flex;justify-content:space-between"><span style="font-weight:500">{{ $meal->name }}</span><span class="badge badge-green">{{ $meal->calories }} سعرة</span></div>
    <div style="font-size:12px;color:var(--color-text-secondary)">{{ $meal->description }}</div>
</div>
@endforeach
</div>
@endif
@if($plan->notes)
<div class="card"><div class="card-title"><i class="ti ti-notes"></i> ملاحظات</div><div style="font-size:13px;color:var(--color-text-secondary)">{{ $plan->notes }}</div></div>
@endif
@endsection
