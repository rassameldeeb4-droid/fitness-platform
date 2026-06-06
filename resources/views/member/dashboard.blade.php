@extends('layouts.app')
@section('title', 'لوحتي')
@section('content')
<div class="page-title">لوحتي الشخصية</div>
<div class="stat-grid">
    <div class="stat-card"><div class="stat-label">الوزن الحالي</div><div class="stat-value">{{ $profile->current_weight ?? '—' }} <span style="font-size:14px">كغ</span></div><div class="stat-sub">الهدف: {{ $profile->target_weight ?? '—' }} كغ</div></div>
    <div class="stat-card"><div class="stat-label">التقدم العام</div><div class="stat-value" style="color:#1D9E75">{{ $profile->progress_percentage ?? 0 }}%</div></div>
    <div class="stat-card"><div class="stat-label">التمارين هذا الأسبوع</div><div class="stat-value">{{ $profile->workout_days_per_week ?? '—' }}</div></div>
    @php $meals = $nutritionPlan->meals ?? collect(); $totalCal = $meals->sum('calories'); @endphp
    <div class="stat-card"><div class="stat-label">السعرات اليوم</div><div class="stat-value">{{ $totalCal ?: '—' }}</div><div class="stat-sub">الهدف: {{ $nutritionPlan->daily_calories ?? '—' }}</div></div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
    <div class="card">
        <div class="card-title">وجباتي اليوم</div>
        @if($nutritionPlan && $nutritionPlan->meals->count())
            @foreach($nutritionPlan->meals as $meal)
            <div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:10px 12px;margin-bottom:8px">
                <div style="display:flex;justify-content:space-between"><span style="font-size:13px;font-weight:500">{{ $meal->name }}</span><span class="badge badge-green">{{ $meal->calories }} سعرة</span></div>
                <div style="font-size:11px;color:var(--color-text-secondary)">{{ $meal->description }}</div>
            </div>
            @endforeach
        @else
            <div style="text-align:center;padding:2rem;color:var(--color-text-tertiary);font-size:13px">لم يتم تعيين خطة غذائية بعد</div>
        @endif
    </div>
    <div class="card">
        <div class="card-title">تمريني اليوم</div>
        @if($workoutPlan && $workoutPlan->days->count())
            @php $todayWorkout = $workoutPlan->days->first(); @endphp
            @foreach(($todayWorkout->exercises ?? []) as $ex)
            <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:13px">
                <span>{{ $ex->name }}</span>
                <span class="badge badge-blue">{{ $ex->pivot->sets ?? 3 }}×{{ $ex->pivot->reps ?? 12 }}</span>
            </div>
            @endforeach
        @else
            <div style="text-align:center;padding:2rem;color:var(--color-text-tertiary);font-size:13px">لم يتم تعيين خطة تدريبية بعد</div>
        @endif
    </div>
</div>

<div class="page-title" style="margin-top:1.5rem">آخر الأنشطة</div>
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
@forelse($timelineEvents as $e)
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
@endsection
