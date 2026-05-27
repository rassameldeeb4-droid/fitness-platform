@php
$colors = [['bg'=>'#E1F5EE','tc'=>'#0F6E56'],['bg'=>'#FAEEDA','tc'=>'#854F0B'],['bg'=>'#EEEDFE','tc'=>'#3C3489'],['bg'=>'#E6F1FB','tc'=>'#185FA5']];
@endphp
@extends('layouts.app')
@section('title', 'الأطباء')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">إدارة الأطباء</div>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة طبيب</a>
</div>
<div class="stat-grid" style="grid-template-columns:repeat(2,1fr)">
    <div class="stat-card"><div class="stat-label">إجمالي الأطباء</div><div class="stat-value">{{ $doctors->total() }}</div></div>
    <div class="stat-card"><div class="stat-label">الأطباء المسجلون</div><div class="stat-value" style="color:#1D9E75">{{ $doctors->count() }}</div></div>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>الطبيب</th><th>البريد</th><th>الجوال</th><th>تاريخ التسجيل</th><th></th></tr></thead>
        <tbody>
            @forelse($doctors as $i => $doc)
            @php $c = $colors[$i % 4]; @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="avatar" style="width:40px;height:40px;background:{{ $c['bg'] }};color:{{ $c['tc'] }};font-size:14px">{{ substr($doc->name, 0, 1) }}</div>
                        <div>
                            <a href="{{ route('admin.doctors.show', $doc->id) }}" style="font-weight:500;color:inherit;text-decoration:none">{{ $doc->name }}</a>
                        </div>
                    </div>
                </td>
                <td style="font-size:13px">{{ $doc->email }}</td>
                <td style="font-size:13px">{{ $doc->phone ?? '—' }}</td>
                <td style="font-size:12px;color:var(--color-text-secondary)">{{ $doc->created_at->format('Y-m-d') }}</td>
                <td>
                    <div style="display:flex;gap:6px;justify-content:end">
                        <a href="{{ route('admin.doctors.edit', $doc->id) }}" class="btn" style="padding:4px 10px;font-size:12px"><i class="ti ti-edit"></i></a>
                        <form method="POST" action="{{ route('admin.doctors.destroy', $doc->id) }}" onsubmit="return confirm('حذف {{ $doc->name }}؟')" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" style="padding:4px 10px;font-size:12px;color:#A32D2D"><i class="ti ti-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--color-text-secondary);padding:2rem">لا يوجد أطباء</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:1rem">{{ $doctors->links() }}</div>
@endsection
