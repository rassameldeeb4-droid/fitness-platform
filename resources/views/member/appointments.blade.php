@extends('layouts.app')
@section('title', 'مواعيدي')
@section('content')
<div class="page-title">مواعيدي</div>
@php $statusMap = ['pending' => 'badge-amber', 'confirmed' => 'badge-blue', 'completed' => 'badge-green', 'cancelled' => 'badge-gray']; @endphp
@php $statusLabels = ['pending' => 'معلق', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'cancelled' => 'ملغي']; @endphp
@forelse($appointments as $a)
<div class="card" style="margin-bottom:8px">
    <div style="display:flex;align-items:center;gap:10px">
        <div class="avatar" style="width:44px;height:44px;background:#EEEDFE;color:#3C3489;font-size:14px">{{ substr($a->doctor->name, 0, 1) }}</div>
        <div style="flex:1">
            <div style="font-size:14px;font-weight:500">{{ $a->doctor->name }}</div>
            <div style="font-size:12px;color:var(--color-text-secondary)">
                {{ $a->scheduled_at->format('Y-m-d h:i A') }} • {{ $a->duration_minutes }} دقيقة
            </div>
        </div>
        <span class="badge {{ $statusMap[$a->status] }}">{{ $statusLabels[$a->status] }}</span>
    </div>
    @if($a->notes)
    <div style="margin-top:8px;font-size:12px;color:var(--color-text-secondary);background:var(--color-background-primary);padding:8px 10px;border-radius:var(--border-radius-md)">{{ $a->notes }}</div>
    @endif
</div>
@empty
<div class="card" style="text-align:center;padding:2rem">
    <i class="ti ti-calendar" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem;display:block"></i>
    <p style="color:var(--color-text-secondary);font-size:14px">لا توجد مواعيد مسجلة</p>
</div>
@endforelse
<div style="margin-top:1rem">{{ $appointments->links() }}</div>
@endsection
