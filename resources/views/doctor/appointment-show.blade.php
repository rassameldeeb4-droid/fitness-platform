@extends('layouts.app')
@section('title', 'تفاصيل الموعد')
@section('content')
@php $statusMap = ['pending' => 'badge-amber', 'confirmed' => 'badge-blue', 'completed' => 'badge-green', 'cancelled' => 'badge-gray']; @endphp
@php $statusLabels = ['pending' => 'معلق', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'cancelled' => 'ملغي']; @endphp
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">تفاصيل الموعد</div>
    <a href="{{ route('doctor.appointments') }}" class="btn"><i class="ti ti-arrow-right"></i> العودة</a>
</div>
<div class="card">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem">
        <div class="avatar" style="width:48px;height:48px;background:#E1F5EE;color:#0F6E56;font-size:16px">{{ substr($appointment->member->name, 0, 1) }}</div>
        <div>
            <div style="font-size:16px;font-weight:500">{{ $appointment->member->name }}</div>
            <div style="font-size:12px;color:var(--color-text-secondary)">{{ $appointment->member->memberProfile->goal ?? '—' }}</div>
        </div>
        <span class="badge {{ $statusMap[$appointment->status] }}" style="margin-right:auto">{{ $statusLabels[$appointment->status] }}</span>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div><span style="color:var(--color-text-secondary);font-size:12px">التاريخ</span><div style="font-size:15px;font-weight:500">{{ $appointment->scheduled_at->format('Y-m-d') }}</div></div>
        <div><span style="color:var(--color-text-secondary);font-size:12px">الوقت</span><div style="font-size:15px;font-weight:500">{{ $appointment->scheduled_at->format('h:i A') }}</div></div>
        <div><span style="color:var(--color-text-secondary);font-size:12px">المدة</span><div style="font-size:15px;font-weight:500">{{ $appointment->duration_minutes }} دقيقة</div></div>
        <div><span style="color:var(--color-text-secondary);font-size:12px">تاريخ الحجز</span><div style="font-size:15px;font-weight:500">{{ $appointment->created_at->format('Y-m-d') }}</div></div>
    </div>
    @if($appointment->notes)
    <div style="margin-top:1rem;background:var(--color-background-primary);padding:10px;border-radius:var(--border-radius-md)">
        <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:4px">الملاحظات</div>
        <div style="font-size:13px">{{ $appointment->notes }}</div>
    </div>
    @endif
    @if($appointment->cancellation_reason)
    <div style="margin-top:8px;background:#FDE8E8;padding:10px;border-radius:var(--border-radius-md)">
        <div style="font-size:12px;color:#A32D2D;margin-bottom:4px">سبب الإلغاء</div>
        <div style="font-size:13px;color:#A32D2D">{{ $appointment->cancellation_reason }}</div>
    </div>
    @endif
    @if(in_array($appointment->status, ['pending', 'confirmed']))
    <div style="margin-top:1rem;display:flex;gap:8px">
        <form method="POST" action="{{ route('doctor.appointments.complete', $appointment->id) }}">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> إكمال الجلسة</button>
        </form>
        <form method="POST" action="{{ route('doctor.appointments.cancel', $appointment->id) }}" onsubmit="return confirm('إلغاء الموعد؟')">
            @csrf
            <input type="text" name="reason" placeholder="سبب الإلغاء..." style="padding:8px;border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);font-size:13px;width:auto;display:inline-block">
            <button type="submit" class="btn" style="color:#A32D2D"><i class="ti ti-x"></i> إلغاء</button>
        </form>
    </div>
    @endif
</div>
@endsection
