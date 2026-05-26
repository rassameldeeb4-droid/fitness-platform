@extends('layouts.app')
@section('title', 'خطة التدريب')
@section('content')
<div class="page-title">خطة تدريب {{ $plan->member->name }}</div>
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
@forelse($plan->days as $day)
<div class="card" style="padding:0;overflow:hidden">
    <div style="background:#EEEDFE;padding:10px 14px;display:flex;justify-content:space-between">
        <div><div style="font-size:14px;font-weight:500">{{ $day->day_name }}</div><div style="font-size:12px;color:#534AB7">{{ $day->focus }}</div></div>
        <span class="badge {{ $day->is_completed ? 'badge-green' : 'badge-gray' }}">{{ $day->is_completed ? 'مكتمل' : 'قادم' }}</span>
    </div>
    <div style="padding:10px 14px">
    @foreach($day->exercises as $ex)
    <div style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <div style="width:32px;height:32px;border-radius:var(--border-radius-md);background:#EEEDFE;display:flex;align-items:center;justify-content:center"><i class="ti ti-barbell" style="color:#534AB7;font-size:14px"></i></div>
        <div style="flex:1"><div style="font-size:12px;font-weight:500">{{ $ex->name }}</div><div style="font-size:11px;color:var(--color-text-secondary)">{{ $ex->pivot->sets ?? 3 }} مج × {{ $ex->pivot->reps ?? 12 }} تكرار</div></div>
    </div>
    @endforeach
    </div>
</div>
@empty<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--color-text-secondary)">لم يتم إضافة تمارين</div>@endforelse
</div>
<a href="{{ route('trainer.workout.create', $plan->member_id) }}" class="btn btn-primary"><i class="ti ti-plus"></i> خطة جديدة</a>
@endsection
