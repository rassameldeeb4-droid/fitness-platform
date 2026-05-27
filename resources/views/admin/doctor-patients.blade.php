@extends('layouts.app')
@section('title', 'مرضى ' . $doctor->name)
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">مرضى <strong>{{ $doctor->name }}</strong></div>
    <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn"><i class="ti ti-arrow-right"></i> عودة</a>
</div>
@if($patients->count())
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:10px">
    @php $colors = [['bg'=>'#E1F5EE','tc'=>'#0F6E56'],['bg'=>'#FAEEDA','tc'=>'#854F0B'],['bg'=>'#EEEDFE','tc'=>'#3C3489'],['bg'=>'#E6F1FB','tc'=>'#185FA5']]; @endphp
    @foreach($patients as $i => $p)
    @php $c = $colors[$i % 4]; $mp = $p->memberProfile; @endphp
    <div class="card">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
            <div class="avatar" style="width:44px;height:44px;background:{{ $c['bg'] }};color:{{ $c['tc'] }};font-size:14px">{{ substr($p->name, 0, 1) }}</div>
            <div>
                <div style="font-size:14px;font-weight:500">{{ $p->name }}</div>
                <div style="font-size:11px;color:var(--color-text-secondary)">{{ $mp->goal ?? '—' }} • {{ $mp->current_weight ?? '?' }} كغ</div>
            </div>
        </div>
        <div style="font-size:12px;display:flex;justify-content:space-between;color:var(--color-text-secondary)">
            <span>📞 {{ $p->phone ?? '—' }}</span>
            <span>📅 {{ $p->created_at->format('Y-m-d') }}</span>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card" style="text-align:center;padding:2rem">
    <i class="ti ti-users" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem;display:block"></i>
    <p style="color:var(--color-text-secondary);font-size:14px">لا يوجد مرضى لهذا الطبيب</p>
</div>
@endif
@endsection
