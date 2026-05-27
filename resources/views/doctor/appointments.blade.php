@extends('layouts.app')
@section('title', 'المواعيد')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">المواعيد</div>
    <a href="{{ route('doctor.appointments.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة موعد</a>
</div>
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:1rem">
    <div class="card" style="padding:14px;text-align:center">
        <div style="font-size:24px;font-weight:500;color:#534AB7">{{ $upcoming }}</div>
        <div style="font-size:12px;color:var(--color-text-secondary)">المواعيد القادمة</div>
    </div>
    <div class="card" style="padding:14px;text-align:center">
        <div style="font-size:24px;font-weight:500;color:#1D9E75">{{ $today }}</div>
        <div style="font-size:12px;color:var(--color-text-secondary)">مواعيد اليوم</div>
    </div>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>المريض</th><th>التاريخ</th><th>المدة</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @forelse($appointments as $a)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar" style="width:36px;height:36px;background:#E1F5EE;color:#0F6E56;font-size:12px">{{ substr($a->member->name, 0, 1) }}</div>
                        {{ $a->member->name }}
                    </div>
                </td>
                <td style="font-size:13px">{{ $a->scheduled_at->format('Y-m-d h:i A') }}</td>
                <td style="font-size:13px">{{ $a->duration_minutes }} د</td>
                <td>
                    @php $statusMap = ['pending' => 'badge-amber', 'confirmed' => 'badge-blue', 'completed' => 'badge-green', 'cancelled' => 'badge-gray']; @endphp
                    @php $statusLabels = ['pending' => 'معلق', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'cancelled' => 'ملغي']; @endphp
                    <span class="badge {{ $statusMap[$a->status] }}">{{ $statusLabels[$a->status] }}</span>
                </td>
                <td>
                    <div style="display:flex;gap:4px">
                        <a href="{{ route('doctor.appointments.show', $a->id) }}" class="btn" style="padding:4px 10px;font-size:12px"><i class="ti ti-eye"></i></a>
                        @if(in_array($a->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('doctor.appointments.complete', $a->id) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="btn" style="padding:4px 10px;font-size:12px;color:#1D9E75"><i class="ti ti-check"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--color-text-secondary);padding:2rem">لا توجد مواعيد</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1rem">{{ $appointments->links() }}</div>
@endsection
