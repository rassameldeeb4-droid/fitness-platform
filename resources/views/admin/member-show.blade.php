@extends('layouts.app')
@section('title', 'بروفايل المشترك')
@section('content')
<div class="page-title">{{ $member->name }}</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
<div>
    <div class="card">
        <div class="card-title"><i class="ti ti-user" style="color:#1D9E75"></i> معلومات المشترك</div>
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:1rem">
            <div class="avatar" style="width:56px;height:56px;background:#E1F5EE;color:#0F6E56;font-size:20px">{{ substr($member->name, 0, 1) }}</div>
            <div>
                <div style="font-size:17px;font-weight:500">{{ $member->name }}</div>
                <div style="font-size:12px;color:var(--color-text-secondary)">{{ $member->email }} • {{ $member->phone ?? '—' }}</div>
                <div style="margin-top:4px"><span class="badge badge-green">عضوية نشطة</span></div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center"><div style="font-size:16px;font-weight:500;color:#1D9E75">{{ $member->memberProfile->current_weight ?? '—' }} كغ</div><div style="font-size:10px;color:var(--color-text-secondary)">الوزن</div></div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center"><div style="font-size:16px;font-weight:500;color:#534AB7">{{ $member->memberProfile->height ?? '—' }} سم</div><div style="font-size:10px;color:var(--color-text-secondary)">الطول</div></div>
            <div style="background:var(--color-background-secondary);border-radius:var(--border-radius-md);padding:8px;text-align:center"><div style="font-size:16px;font-weight:500;color:#185FA5">{{ $member->memberProfile->body_fat ?? '—' }}%</div><div style="font-size:10px;color:var(--color-text-secondary)">الدهون</div></div>
        </div>
    </div>
</div>
<div>
    <div class="card">
        <div class="card-title"><i class="ti ti-credit-card" style="color:#534AB7"></i> الاشتراكات</div>
        @forelse($member->subscriptions as $sub)
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:13px">
            <div><div style="font-weight:500">{{ $sub->package->name ?? 'باقة' }}</div><div style="font-size:11px;color:var(--color-text-secondary)">{{ $sub->start_date }} → {{ $sub->end_date }}</div></div>
            <span class="badge {{ $sub->status === 'active' ? 'badge-green' : 'badge-red' }}">{{ $sub->status }}</span>
        </div>
        @empty<p style="color:var(--color-text-secondary);font-size:13px">لا يوجد اشتراكات</p>@endforelse
    </div>
    <div class="card">
        <div class="card-title"><i class="ti ti-chart-line" style="color:#1D9E75"></i> آخر القياسات</div>
        @foreach($member->progressLogs->take(5) as $log)
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:12px">
            <span style="color:var(--color-text-secondary)">{{ $log->logged_date }}</span>
            <span>{{ $log->weight }} كغ</span><span style="color:#534AB7">{{ $log->body_fat }}%</span>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
