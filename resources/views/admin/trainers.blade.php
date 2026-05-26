@extends('layouts.app')
@section('title', 'المدربون')
@section('content')
<div class="page-title">إدارة المدربين</div>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="stat-label">إجمالي المدربين</div><div class="stat-value">{{ $trainers->total() }}</div></div>
    <div class="stat-card"><div class="stat-label">متاحون الآن</div><div class="stat-value" style="color:#1D9E75">8</div></div>
    <div class="stat-card"><div class="stat-label">متوسط التقييم</div><div class="stat-value">4.7 ★</div></div>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>المدرب</th><th>التخصص</th><th>عدد العملاء</th><th>التقييم</th><th>الحالة</th></tr></thead>
        <tbody>
            @forelse($trainers as $trainer)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar" style="width:36px;height:36px;background:#EEEDFE;color:#3C3489;font-size:12px">{{ substr($trainer->name, 0, 1) }}</div>
                        {{ $trainer->name }}
                    </div>
                </td>
                <td>{{ $trainer->trainerProfile->specialty ?? '—' }}</td>
                <td>{{ $trainer->memberProfile->trainer_id ?? 0 }} عميل</td>
                <td style="color:#854F0B">★ {{ $trainer->trainerProfile->rating ?? 0 }}</td>
                <td><span class="badge {{ ($trainer->trainerProfile->available ?? false) ? 'badge-green' : 'badge-amber' }}">{{ ($trainer->trainerProfile->available ?? false) ? 'متاح' : 'مشغول' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--color-text-secondary)">لا يوجد مدربون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
