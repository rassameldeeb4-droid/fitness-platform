@extends('layouts.app')
@section('title', 'عيادتي')
@section('content')
<div class="page-title">عيادتي</div>
<div style="display:grid;grid-template-columns:1fr 300px;gap:1rem">
<div>
<div class="timeline" style="position:relative;padding-right:20px">
<div style="position:absolute;right:8px;top:0;bottom:0;width:1px;background:var(--color-border-tertiary)"></div>
@php
$typeMeta = [
    'nutrition_plan_assigned' => ['dot' => '#534AB7', 'tag' => 'غذائي', 'tagc' => 'badge-purple'],
    'workout_plan_assigned'  => ['dot' => '#854F0B', 'tag' => 'تمرين', 'tagc' => 'badge-amber'],
    'progress_logged'        => ['dot' => '#185FA5', 'tag' => 'قياسات', 'tagc' => 'badge-blue'],
    'member_added'           => ['dot' => '#1D9E75', 'tag' => 'جديد', 'tagc' => 'badge-green'],
];
@endphp
@forelse($recentEvents as $e)
@php $m = $typeMeta[$e->type] ?? ['dot' => '#1D9E75', 'tag' => 'عام', 'tagc' => 'badge-green']; @endphp
<div style="position:relative;margin-bottom:1rem">
    <div style="position:absolute;right:-16px;top:4px;width:10px;height:10px;border-radius:50%;background:{{ $m['dot'] }};border:2px solid var(--color-background-primary)"></div>
    <div style="font-size:11px;color:var(--color-text-tertiary);margin-bottom:3px">{{ $e->created_at->diffForHumans() }}</div>
    <div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:9px 12px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:6px">
            <div>
                <div style="font-size:13px;font-weight:500">{{ $e->title }}</div>
                <div style="font-size:12px;color:var(--color-text-secondary);margin-top:2px">{{ $e->description }}</div>
            </div>
            <span class="badge {{ $m['tagc'] }}" style="flex-shrink:0">{{ $m['tag'] }}</span>
        </div>
    </div>
</div>
@empty
<div style="text-align:center;padding:2rem;color:var(--color-text-tertiary);font-size:13px">لا توجد أحداث بعد</div>
@endforelse
</div>
</div>

<div>
<div class="card">
    <div class="card-title"><i class="ti ti-chart-bar" style="color:#1D9E75"></i> إحصائياتي</div>
    <div style="display:flex;flex-direction:column;gap:8px">
        @php $stats = [['المرضى',$patientCount,'badge-green'],['خطط غذائية',$nutritionPlansCount,'badge-purple'],['خطط تدريبية',$workoutPlansCount,'badge-blue']]; @endphp
        @foreach($stats as $s)
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:4px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
            <span style="color:var(--color-text-secondary)">{{ $s[0] }}</span>
            <span class="badge {{ $s[2] }}">{{ $s[1] }}</span>
        </div>
        @endforeach
    </div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-calendar" style="color:#534AB7"></i> المواعيد القادمة</div>
    @forelse($upcomingAppointments as $a)
    <a href="{{ route('doctor.appointments.show', $a->id) }}" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary);text-decoration:none;color:inherit;font-size:13px">
        <span>{{ $a->member->name }}</span>
        <span style="color:var(--color-text-tertiary);font-size:11px">{{ $a->scheduled_at->format('h:i A') }}</span>
    </a>
    @empty
    <div style="text-align:center;padding:1rem;color:var(--color-text-tertiary);font-size:13px">لا توجد مواعيد قادمة</div>
    @endforelse
</div>
</div>
</div>
@endsection
