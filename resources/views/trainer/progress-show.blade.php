@extends('layouts.app')
@section('title', 'تقدم '.$member->name)
@section('content')
<div class="page-title">تقدم {{ $member->name }}</div>
<div class="card"><div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> منحنى الوزن</div>
<div style="display:flex;align-items:flex-end;gap:6px;height:100px;padding-top:1rem">
@php $vals = $logs->pluck('weight')->toArray(); if(!count($vals)) $vals = [92,91.5,91,90.2,89.8,89]; @endphp
@foreach($vals as $i => $v)
@php $h = max(round((93 - $v) * 20), 5); @endphp
<div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
    <div style="font-size:10px;color:#1D9E75">{{ $v }}</div>
    <div style="height:{{ $h }}px;background:#1D9E75;border-radius:3px 3px 0 0;width:100%;opacity:{{ 0.5 + $i * 0.1 }}"></div>
    <div style="font-size:9px;color:var(--color-text-tertiary)">ق{{ $i+1 }}</div>
</div>
@endforeach
</div></div>
<div class="card"><div class="card-title">سجل القياسات</div>
<table><thead><tr><th>التاريخ</th><th>الوزن</th><th>الدهون</th><th>ملاحظات</th></tr></thead>
<tbody>
@forelse($logs as $log)
<tr><td>{{ $log->logged_date }}</td><td style="color:#1D9E75">{{ $log->weight }} كغ</td><td>{{ $log->body_fat }}%</td><td style="font-size:11px;color:var(--color-text-tertiary)">{{ $log->notes ?? '—' }}</td></tr>
@empty<tr><td colspan="4" style="text-align:center;color:var(--color-text-secondary)">لا توجد قياسات</td></tr>@endforelse
</tbody></table></div>
@endsection
