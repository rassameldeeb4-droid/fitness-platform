@extends('layouts.app')
@section('title', $doctor->name)
@section('content')
<div style="display:flex;align-items:center;gap:14px;margin-bottom:1.25rem">
    <div class="avatar" style="width:56px;height:56px;background:#EEEDFE;color:#3C3489;font-size:22px">{{ substr($doctor->name, 0, 1) }}</div>
    <div>
        <div style="font-size:22px;font-weight:500">{{ $doctor->name }}</div>
        <div style="font-size:13px;color:var(--color-text-secondary)">{{ $doctor->email }} @if($doctor->phone) • {{ $doctor->phone }} @endif</div>
    </div>
    <div style="margin-right:auto;display:flex;gap:6px">
        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn"><i class="ti ti-edit"></i> تعديل</a>
        <a href="{{ route('admin.doctors.patients', $doctor->id) }}" class="btn"><i class="ti ti-users"></i> المرضى</a>
    </div>
</div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:1rem">
    <div class="card" style="padding:14px;text-align:center">
        <div style="font-size:24px;font-weight:500;color:#534AB7">{{ $patientCount }}</div>
        <div style="font-size:12px;color:var(--color-text-secondary)">عدد المرضى</div>
    </div>
    <div class="card" style="padding:14px;text-align:center">
        <div style="font-size:24px;font-weight:500;color:#1D9E75">{{ $doctor->created_at->format('Y-m-d') }}</div>
        <div style="font-size:12px;color:var(--color-text-secondary)">تاريخ التسجيل</div>
    </div>
    <div class="card" style="padding:14px;text-align:center">
        <div style="font-size:24px;font-weight:500;color:#185FA5">{{ $doctor->updated_at->diffForHumans() }}</div>
        <div style="font-size:12px;color:var(--color-text-secondary)">آخر تحديث</div>
    </div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-activity"></i> آخر النشاطات</div>
    @forelse($doctor->timelineEvents()->latest()->take(10)->get() as $event)
    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:13px">
        <span>{{ $event->title }}</span>
        <span style="color:var(--color-text-tertiary);font-size:11px">{{ $event->created_at->diffForHumans() }}</span>
    </div>
    @empty
    <p style="color:var(--color-text-secondary);font-size:13px">لا توجد نشاطات</p>
    @endforelse
</div>
@endsection
