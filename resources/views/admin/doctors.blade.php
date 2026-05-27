@extends('layouts.app')
@section('title', 'الأطباء')
@section('content')
<div class="page-title">إدارة الأطباء</div>
<div class="stat-grid" style="grid-template-columns:repeat(2,1fr)">
    <div class="stat-card"><div class="stat-label">إجمالي الأطباء</div><div class="stat-value">{{ $doctors->total() }}</div></div>
    <div class="stat-card"><div class="stat-label">الأطباء النشطون</div><div class="stat-value" style="color:#1D9E75">{{ $doctors->count() }}</div></div>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>الطبيب</th><th>البريد</th><th>الجوال</th><th>تاريخ التسجيل</th></tr></thead>
        <tbody>
            @forelse($doctors as $doc)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar" style="width:36px;height:36px;background:#E8F5E9;color:#1D9E75;font-size:12px">{{ substr($doc->name, 0, 1) }}</div>
                        {{ $doc->name }}
                    </div>
                </td>
                <td>{{ $doc->email }}</td>
                <td>{{ $doc->phone ?? '—' }}</td>
                <td style="font-size:12px;color:var(--color-text-secondary)">{{ $doc->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:var(--color-text-secondary)">لا يوجد أطباء</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
