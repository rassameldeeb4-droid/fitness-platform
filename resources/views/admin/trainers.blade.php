@extends('layouts.app')
@section('title', 'المدربون')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">إدارة المدربين</div>
    <a href="{{ route('admin.trainers.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة مدرب</a>
</div>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr)">
    <div class="stat-card"><div class="stat-label">إجمالي المدربين</div><div class="stat-value">{{ $trainers->total() }}</div></div>
    <div class="stat-card"><div class="stat-label">متاحون الآن</div><div class="stat-value" style="color:#1D9E75">{{ $trainers->filter(fn($t)=>$t->trainerProfile?->available)->count() }}</div></div>
    <div class="stat-card"><div class="stat-label">متوسط التقييم</div><div class="stat-value">{{ number_format($trainers->avg(fn($t)=>$t->trainerProfile?->rating ?? 0), 1) }} ★</div></div>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>المدرب</th><th>التخصص</th><th>التقييم</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @forelse($trainers as $t)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar" style="width:36px;height:36px;background:#EEEDFE;color:#3C3489;font-size:12px">{{ substr($t->name, 0, 1) }}</div>
                        <a href="{{ route('admin.trainers.show', $t->id) }}" style="font-weight:500;color:inherit;text-decoration:none">{{ $t->name }}</a>
                    </div>
                </td>
                <td>{{ $t->trainerProfile->specialty ?? '—' }}</td>
                <td style="color:#854F0B">★ {{ $t->trainerProfile->rating ?? '0.0' }}</td>
                <td><span class="badge {{ ($t->trainerProfile->available ?? false) ? 'badge-green' : 'badge-amber' }}">{{ ($t->trainerProfile->available ?? false) ? 'متاح' : 'مشغول' }}</span></td>
                <td>
                    <div style="display:flex;gap:6px;justify-content:end">
                        <a href="{{ route('admin.trainers.edit', $t->id) }}" class="btn" style="padding:4px 10px;font-size:12px"><i class="ti ti-edit"></i></a>
                        <a href="{{ route('admin.trainers.trainees', $t->id) }}" class="btn" style="padding:4px 10px;font-size:12px"><i class="ti ti-users"></i></a>
                        <form method="POST" action="{{ route('admin.trainers.destroy', $t->id) }}" onsubmit="return confirm('حذف {{ $t->name }}؟')" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" style="padding:4px 10px;font-size:12px;color:#A32D2D"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--color-text-secondary)">لا يوجد مدربون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1rem">{{ $trainers->links() }}</div>
@endsection
