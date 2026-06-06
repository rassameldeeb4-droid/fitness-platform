@extends('layouts.app')
@section('title', 'متدربيّ')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">متدربيّ</div>
    <a href="{{ route('trainer.trainees.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة متدرب</a>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:10px">
@php
$colors = [['bg'=>'#E1F5EE','tc'=>'#0F6E56'],['bg'=>'#FAEEDA','tc'=>'#854F0B'],['bg'=>'#EEEDFE','tc'=>'#3C3489'],['bg'=>'#E6F1FB','tc'=>'#185FA5']];
@endphp
@forelse($trainees as $i => $trainee)
@php $c = $colors[$i % 4]; $mp = $trainee->memberProfile; @endphp
<div class="card" style="cursor:pointer">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
        <div class="avatar" style="width:48px;height:48px;background:{{ $c['bg'] }};color:{{ $c['tc'] }};font-size:16px">{{ substr($trainee->name, 0, 1) }}</div>
        <div>
            <div style="font-size:15px;font-weight:500;margin-bottom:2px"><a href="{{ route('trainer.trainees.show', $trainee->id) }}">{{ $trainee->name }}</a></div>
            <div style="font-size:12px;color:var(--color-text-secondary)">{{ $mp->goal ?? '—' }} • {{ $mp->current_weight ?? '?' }} كغ</div>
            <div style="color:#854F0B;font-size:12px">@for($s=1;$s<=5;$s++){{ $s <= round(($mp->progress_percentage ?? 0) / 20) ? '★' : '☆' }}@endfor</div>
        </div>
    </div>
    <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:4px;display:flex;justify-content:space-between"><span>التقدم</span><span>{{ $mp->progress_percentage ?? 0 }}%</span></div>
    <div class="progress-bar"><div class="progress-fill" style="width:{{ $mp->progress_percentage ?? 0 }}%"></div></div>
    <div style="display:flex;gap:6px;margin-top:10px">
        <a href="{{ route('chat.index') }}" class="btn" style="flex:1;font-size:12px;padding:6px;text-align:center"><i class="ti ti-message"></i> رسالة</a>
        <a href="{{ route('trainer.nutrition.create', $trainee->id) }}" class="btn btn-primary" style="flex:1;font-size:12px;padding:6px;text-align:center"><i class="ti ti-salad"></i> غذائي</a>
        <a href="{{ route('trainer.workout.create', $trainee->id) }}" class="btn btn-primary" style="flex:1;font-size:12px;padding:6px;text-align:center"><i class="ti ti-barbell"></i> تمرين</a>
    </div>
</div>
@empty
<div class="card" style="grid-column:1/-1;text-align:center;color:var(--color-text-secondary);padding:2rem">ليس لديك متدربون بعد</div>
@endforelse
</div>
@endsection
