@extends('layouts.app')
@section('title', 'تقدمي')
@section('content')
<div class="page-title">تقدمي</div>
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
    <div class="stat-card"><div class="stat-label">الوزن الابتدائي</div><div class="stat-value">92 كغ</div></div>
    <div class="stat-card"><div class="stat-label">الوزن الحالي</div><div class="stat-value" style="color:#1D9E75">{{ $profile->current_weight ?? 89 }} كغ</div></div>
    <div class="stat-card"><div class="stat-label">الخسارة</div><div class="stat-value" style="color:#1D9E75">{{ 92 - ($profile->current_weight ?? 89) }} كغ</div></div>
    <div class="stat-card"><div class="stat-label">الهدف</div><div class="stat-value">{{ $profile->target_weight ?? 82 }} كغ</div></div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> منحنى التقدم</div>
    @php $weights = $logs->pluck('weight')->reverse()->values(); $vals = $weights->count() ? $weights->toArray() : [92,91.5,91,90.2,89.8,89]; @endphp
    <div style="display:flex;align-items:flex-end;gap:6px;height:100px;padding-top:1rem">
        @foreach($vals as $i => $v)
        @php $h = max(round((93 - $v) * 20), 5); @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
            <div style="font-size:10px;color:#1D9E75">{{ $v }}</div>
            <div style="height:{{ $h }}px;background:#1D9E75;border-radius:3px 3px 0 0;width:100%;opacity:{{ 0.5 + $i * 0.1 }}"></div>
            <div style="font-size:9px;color:var(--color-text-tertiary)">أ{{ $i+1 }}</div>
        </div>
        @endforeach
    </div>
    <div style="margin-top:1rem;font-size:13px;color:var(--color-text-secondary)">متبقي حتى الهدف: {{ $profile->current_weight - $profile->target_weight ?? 7 }} كغ — استمر هكذا! 💪</div>
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
