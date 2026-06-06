@extends('layouts.app')
@section('title', 'تقدمي')
@php
$firstLog = $logs->last();
$firstWeight = $firstLog->weight ?? $profile->current_weight ?? null;
$currentWeight = $profile->current_weight ?? null;
$targetWeight = $profile->target_weight ?? null;
$weightVals = $logs->sortBy('logged_date')->pluck('weight')->values();
@endphp
@section('content')
<div class="page-title">تقدمي</div>
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card"><div class="stat-label">الوزن الابتدائي</div><div class="stat-value">{{ $firstWeight ? $firstWeight.' كغ' : '—' }}</div></div>
    <div class="stat-card"><div class="stat-label">الوزن الحالي</div><div class="stat-value" style="color:#1D9E75">{{ $currentWeight ? $currentWeight.' كغ' : '—' }}</div></div>
    <div class="stat-card"><div class="stat-label">الخسارة</div><div class="stat-value" style="color:#1D9E75">{{ ($firstWeight && $currentWeight) ? ($firstWeight - $currentWeight).' كغ' : '—' }}</div></div>
    <div class="stat-card"><div class="stat-label">الهدف</div><div class="stat-value">{{ $targetWeight ? $targetWeight.' كغ' : '—' }}</div></div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> منحنى التقدم</div>
    @if($weightVals->count())
    <div style="display:flex;align-items:flex-end;gap:6px;height:100px;padding-top:1rem">
        @foreach($weightVals as $i => $v)
        @php $h = max(round((max($weightVals->toArray()) + 2 - $v) * 15), 5); @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
            <div style="font-size:10px;color:#1D9E75">{{ $v }}</div>
            <div style="height:{{ $h }}px;background:#1D9E75;border-radius:3px 3px 0 0;width:100%;opacity:{{ 0.5 + (min(10,$i) * 0.05) }}"></div>
            <div style="font-size:9px;color:var(--color-text-tertiary)">{{ $i+1 }}</div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align:center;padding:2rem;color:var(--color-text-tertiary);font-size:13px">لا توجد قياسات بعد — سجل أول قياس</div>
    @endif
    <div style="margin-top:1rem;font-size:13px;color:var(--color-text-secondary)">{{ ($currentWeight && $targetWeight) ? 'متبقي حتى الهدف: '.($currentWeight - $targetWeight).' كغ' : '' }}</div>
</div>

<div class="card">
    <div class="card-title"><i class="ti ti-plus" style="color:#1D9E75"></i> تسجيل قياسات جديدة</div>
    <form method="POST" action="{{ route('member.progress.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>الوزن (كغ)</label><input name="weight" type="number" step="0.1"></div>
            <div class="input-grp"><label>نسبة الدهون (%)</label><input name="body_fat" type="number" step="0.1"></div>
            <div class="input-grp"><label>الكتلة العضلية</label><input name="muscle_mass" type="number" step="0.1"></div>
        </div>
        <div class="input-grp" style="margin-bottom:12px"><label>التاريخ</label><input name="logged_date" type="date" value="{{ now()->format('Y-m-d') }}"></div>
        <button class="btn btn-primary"><i class="ti ti-check"></i> تسجيل القياسات</button>
    </form>
</div>
@endsection
