@extends('layouts.app')
@section('title', $member->name)
@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem">
    @php $img = $member->memberProfile->image; @endphp
    @if($img)
    <img src="{{ Storage::url($img) }}" alt="" style="width:48px;height:48px;border-radius:50%;object-fit:cover">
    @else
    <div class="avatar" style="width:48px;height:48px;background:#E1F5EE;color:#0F6E56;font-size:18px">{{ substr($member->name, 0, 1) }}</div>
    @endif
    <div><div style="font-size:20px;font-weight:500">{{ $member->name }}</div><div style="font-size:12px;color:var(--color-text-secondary)">{{ $member->memberProfile->goal ?? '—' }} • {{ $member->memberProfile->current_weight ?? '?' }} كغ</div></div>
    <div style="margin-right:auto;display:flex;gap:6px">
        <a href="{{ route('chat.index') }}" class="btn btn-primary" style="font-size:12px"><i class="ti ti-message"></i> رسالة</a>
        <a href="{{ route('trainer.nutrition.create', $member->id) }}" class="btn" style="font-size:12px"><i class="ti ti-salad"></i> غذائي</a>
        <a href="{{ route('trainer.workout.create', $member->id) }}" class="btn" style="font-size:12px"><i class="ti ti-barbell"></i> تمرين</a>
    </div>
</div>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:1rem">
    <div class="card" style="padding:10px;text-align:center"><div style="font-size:22px;font-weight:500;color:#1D9E75">{{ $member->memberProfile->current_weight ?? '—' }} كغ</div><div style="font-size:11px;color:var(--color-text-secondary)">الوزن</div></div>
    <div class="card" style="padding:10px;text-align:center"><div style="font-size:22px;font-weight:500;color:#534AB7">{{ $member->memberProfile->body_fat ?? '—' }}%</div><div style="font-size:11px;color:var(--color-text-secondary)">الدهون</div></div>
    <div class="card" style="padding:10px;text-align:center"><div style="font-size:22px;font-weight:500;color:#185FA5">{{ $member->memberProfile->progress_percentage ?? 0 }}%</div><div style="font-size:11px;color:var(--color-text-secondary)">التقدم</div></div>
    <div class="card" style="padding:10px;text-align:center"><div style="font-size:22px;font-weight:500;color:#854F0B">{{ str_repeat('★', 5) }}</div><div style="font-size:11px;color:var(--color-text-secondary)">التزام</div></div>
</div>
@php $mp = $member->memberProfile; @endphp
@if($mp->injuries || $mp->complaints)
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:1rem">
    @if($mp->injuries)
    <div class="card" style="border-right:3px solid #A32D2D">
        <div class="card-title" style="color:#A32D2D"><i class="ti ti-alert-triangle"></i> الإصابات</div>
        <div style="font-size:13px;color:var(--color-text-secondary);line-height:1.6">{{ $mp->injuries }}</div>
    </div>
    @endif
    @if($mp->complaints)
    <div class="card" style="border-right:3px solid #854F0B">
        <div class="card-title" style="color:#854F0B"><i class="ti ti-notes"></i> الشكاوي والملاحظات</div>
        <div style="font-size:13px;color:var(--color-text-secondary);line-height:1.6">{{ $mp->complaints }}</div>
    </div>
    @endif
</div>
@endif
<div class="card"><div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> سجل القياسات</div>
@forelse($member->progressLogs->take(10) as $log)
<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:12px">
    <span style="color:var(--color-text-tertiary)">{{ $log->logged_date }}</span>
    <span>{{ $log->weight }} كغ</span>
    <span style="color:#534AB7">{{ $log->body_fat }}%</span>
</div>
@empty<p style="color:var(--color-text-secondary);font-size:13px">لا توجد قياسات</p>@endforelse
</div>
@endsection
