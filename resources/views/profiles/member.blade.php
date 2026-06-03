@extends('layouts.app')
@section('title', $member->name)
@section('content')
<div class="page-title">{{ $member->name }}</div>
<div class="card">
    <div style="display:flex;gap:12px;align-items:center;">
        <div class="avatar" style="width:56px;height:56px;background:#E1F5EE;color:#0F6E56;font-size:20px">{{ substr($member->name, 0, 1) }}</div>
        <div>
            <div style="font-size:17px;font-weight:500">{{ $member->name }}</div>
            <div style="font-size:12px;color:var(--color-text-secondary)">{{ $member->email }} • {{ $member->phone ?? '—' }}</div>
            <div style="margin-top:4px"><span class="badge badge-green">عضو</span></div>
        </div>
    </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
    <div class="card">
        <div class="card-title"><i class="ti ti-ruler" style="color:#1D9E75"></i> القياسات</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#1D9E75">{{ $member->memberProfile->current_weight ?? '—' }} كغ</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">الوزن</div>
            </div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#534AB7">{{ $member->memberProfile->height ?? '—' }} سم</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">الطول</div>
            </div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#185FA5">{{ $member->memberProfile->body_fat ?? '—' }}%</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">الدهون</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#0F6E56">{{ $member->memberProfile->target_weight ?? '—' }} كغ</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">الهدف</div>
            </div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#854F0B">{{ $member->memberProfile->bmi ?? '—' }}</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">BMI</div>
            </div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center">
                <div style="font-size:16px;font-weight:500;color:#534AB7">{{ $member->memberProfile->muscle_mass ?? '—' }}%</div>
                <div style="font-size:10px;color:var(--color-text-secondary)">العضلات</div>
            </div>
        </div>
    </div>
    <div>
        <div class="card">
            <div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> الهدف</div>
            <div style="font-size:14px;color:var(--color-text-secondary)">
                @switch($member->memberProfile->goal ?? '')
                    @case('weight_loss') إنقاص وزن @break
                    @case('muscle_gain') تضخيم @break
                    @case('toning') تنشيف @break
                    @default عام
                @endswitch
            </div>
            <div style="margin-top:8px;font-size:12px;color:var(--color-text-secondary)">
                مستوى النشاط: {{ $member->memberProfile->activity_level ?? '—' }} • {{ $member->memberProfile->workout_days_per_week ?? 0 }} أيام/أسبوع
            </div>
        </div>
        @if($member->progressLogs->count())
        <div class="card" style="margin-top:1rem">
            <div class="card-title"><i class="ti ti-trending-up" style="color:#1D9E75"></i> آخر القياسات</div>
            @foreach($member->progressLogs->take(5) as $log)
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:12px">
                <span style="color:var(--color-text-secondary)">{{ $log->logged_date }}</span>
                <span>{{ $log->weight }} كغ</span>
                <span style="color:#534AB7">{{ $log->body_fat }}%</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
