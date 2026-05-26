@extends('layouts.app')
@section('title', 'متابعة التقدم')
@section('content')
<div class="page-title">متابعة التقدم</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:10px">
@forelse($trainees as $trainee)
<div class="card">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
        <div class="avatar" style="width:40px;height:40px;background:#E1F5EE;color:#0F6E56;font-size:14px">{{ substr($trainee->name, 0, 1) }}</div>
        <div><div style="font-size:14px;font-weight:500">{{ $trainee->name }}</div><div style="font-size:12px;color:var(--color-text-secondary)">{{ $trainee->memberProfile->goal ?? '—' }} • {{ $trainee->memberProfile->current_weight ?? '?' }} كغ</div></div>
    </div>
    <div class="progress-bar"><div class="progress-fill" style="width:{{ $trainee->memberProfile->progress_percentage ?? 0 }}%"></div></div>
    <a href="{{ route('trainer.progress.show', $trainee->id) }}" class="btn" style="width:100%;margin-top:8px;text-align:center"><i class="ti ti-chart-line"></i> التفاصيل</a>
</div>
@empty<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--color-text-secondary)">لا يوجد متدربون</div>@endforelse
</div>
@endsection
