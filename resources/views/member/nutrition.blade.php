@extends('layouts.app')
@section('title', 'نظامي الغذائي')
@section('content')
<div class="page-title"><i class="ti ti-salad" style="color:#1D9E75"></i> نظامي الغذائي</div>
@if($plan)
<div style="background:#EEEDFE;border:0.5px solid #AFA9EC;border-radius:12px;padding:1.25rem;margin-bottom:1rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem">
        <div style="font-size:14px;font-weight:500;color:#3C3489">{{ $plan->goal }} • {{ $plan->daily_calories }} سعرة</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px">
        <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#534AB7">{{ $plan->daily_calories }}</div><div style="font-size:11px;color:var(--color-text-secondary)">سعرة</div></div>
        <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#1D9E75">{{ $plan->protein }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">بروتين</div></div>
        <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#185FA5">{{ $plan->carbs }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">كارب</div></div>
        <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#854F0B">{{ $plan->fat }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">دهون</div></div>
        <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:18px;font-weight:500;color:#0F6E56">{{ $plan->fiber ?? 0 }}غ</div><div style="font-size:11px;color:var(--color-text-secondary)">ألياف</div></div>
    </div>
</div>

<div style="display:grid;gap:10px">
    @php $colors = ['#534AB7','#1D9E75','#185FA5','#854F0B','#A32D2D']; @endphp
    @foreach($plan->meals as $i => $meal)
    <div class="card" style="padding:0;overflow:hidden;border-right:4px solid {{ $colors[$i % 5] }}">
        <div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;background:var(--color-background-secondary)">
            <div>
                <span style="font-size:14px;font-weight:500">{{ $meal->name }}</span>
                @if($meal->time)<span style="font-size:11px;color:var(--color-text-tertiary);margin-right:6px">{{ $meal->time }}</span>@endif
            </div>
            <span class="badge badge-green">{{ $meal->calories }} سعرة</span>
        </div>
        <div style="padding:10px 14px">
            <div style="display:flex;gap:10px;margin-bottom:6px;flex-wrap:wrap">
                <span style="font-size:11px;background:#EEEDFE;padding:2px 8px;border-radius:4px;color:#534AB7">بروتين: {{ $meal->protein ?? 0 }}غ</span>
                <span style="font-size:11px;background:#E6F1FB;padding:2px 8px;border-radius:4px;color:#185FA5">كارب: {{ $meal->carbs ?? 0 }}غ</span>
                <span style="font-size:11px;background:#FAEEDA;padding:2px 8px;border-radius:4px;color:#854F0B">دهون: {{ $meal->fat ?? 0 }}غ</span>
            </div>
            @if($meal->items && count($meal->items))
            <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:6px;line-height:1.7">
                @foreach($meal->items as $item)
                <span style="display:inline-block;background:var(--color-background-primary);padding:1px 8px;border-radius:4px;border:0.5px solid var(--color-border-tertiary);margin-left:4px;margin-bottom:4px">{{ $item }}</span>
                @endforeach
            </div>
            @endif
            @php
                $desc = $meal->description ?? '';
                $vitamins = '';
                $minerals = '';
                if (preg_match('/فيتامينات:\s*(.+)/', $desc, $m)) $vitamins = $m[1];
                if (preg_match('/معادن:\s*(.+)/', $desc, $m)) $minerals = $m[1];
            @endphp
            @if($vitamins)
            <div style="font-size:11px;color:#0F6E56;margin-bottom:2px"><i class="ti ti-vitamin" style="font-size:10px"></i> فيتامينات: {{ $vitamins }}</div>
            @endif
            @if($minerals)
            <div style="font-size:11px;color:#854F0B"><i class="ti ti-flask" style="font-size:10px"></i> معادن: {{ $minerals }}</div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@php
    $water = '';
    if ($plan->notes && preg_match('/الماء:\s*(.+)/', $plan->notes, $m)) $water = $m[1];
@endphp
@if($water)
<div style="margin-top:10px;background:#E1F5EE;border-right:3px solid #1D9E75;border-radius:8px;padding:10px 14px;font-size:13px;color:#0F6E56"><i class="ti ti-droplet"></i> {{ $water }}</div>
@endif
@else
<div class="card" style="text-align:center;padding:2rem">
    <i class="ti ti-salad" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem;display:block"></i>
    <p style="color:var(--color-text-secondary);font-size:14px">لم يتم إنشاء خطة غذائية لك بعد</p>
    <p style="color:var(--color-text-tertiary);font-size:12px;margin-top:4px">تواصل مع مدربك ليُعدّ لك خطة مخصصة</p>
</div>
@endif
@endsection
