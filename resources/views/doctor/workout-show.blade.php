@extends('layouts.app')
@section('title', 'خطة التدريب')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">خطة التدريب لـ {{ $plan->member->name }}</div>
    <a href="{{ route('doctor.patients.show', $plan->member_id) }}" class="btn">عودة</a>
</div>
<div class="stat-grid">
    <div class="stat-card"><div class="stat-label">الهدف</div><div class="stat-value" style="font-size:16px">{{ $plan->goal ?? 'عام' }}</div></div>
    <div class="stat-card"><div class="stat-label">المستوى</div><div class="stat-value" style="font-size:16px">{{ $plan->level }}</div></div>
    <div class="stat-card"><div class="stat-label">أيام/أسبوع</div><div class="stat-value" style="color:#534AB7">{{ $plan->days_per_week }}</div></div>
</div>
@if($plan->days->count())
<div class="card"><div class="card-title"><i class="ti ti-barbell"></i> أيام التمرين</div>
@foreach($plan->days as $day)
<div style="border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:10px 14px;margin-bottom:8px">
    <div style="font-weight:500;margin-bottom:6px">{{ $day->name ?? 'اليوم ' . $loop->iteration }}</div>
    @foreach($day->exercises as $ex)
    <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--color-text-secondary);padding:2px 0">
        <span>{{ $ex->name }}</span>
        <span>{{ $ex->pivot->sets ?? 3 }}×{{ $ex->pivot->reps ?? 12 }}</span>
    </div>
    @endforeach
</div>
@endforeach
</div>
@endif
@if($plan->notes)
<div class="card"><div class="card-title"><i class="ti ti-notes"></i> ملاحظات</div><div style="font-size:13px;color:var(--color-text-secondary)">{{ $plan->notes }}</div></div>
@endif
@endsection
