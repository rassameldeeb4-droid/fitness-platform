@extends('layouts.app')
@section('title', 'المنتجات')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">المنتجات</div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة منتج</a>
</div>
<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>الفئة</th>
                <th>السعر</th>
                <th>التقييم</th>
                <th>الحالة</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
            <tr>
                <td style="color:var(--color-text-tertiary)">{{ $p->id }}</td>
                <td>
                    @if($p->image)
                    <img src="{{ Storage::url($p->image) }}" alt="" style="width:36px;height:36px;border-radius:6px;object-fit:cover">
                    @else
                    <div style="width:36px;height:36px;border-radius:6px;background:var(--color-background-secondary);display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--color-text-tertiary)">—</div>
                    @endif
                </td>
                <td>{{ $p->name }}</td>
                <td><span class="badge badge-green">{{ $p->category }}</span></td>
                <td>{{ number_format($p->price, 2) }} ر.س</td>
                <td>@if($p->rating) {{ $p->rating }} ★ ({{ $p->review_count }}) @else — @endif</td>
                <td><span class="badge {{ $p->is_active ? 'badge-green' : 'badge-red' }}">{{ $p->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                <td>
                    <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm"><i class="ti ti-edit"></i></a>
                    <form method="POST" action="{{ route('admin.products.destroy', $p) }}" style="display:inline" onsubmit="return confirm('حذف المنتج؟')">@csrf @method('DELETE')<button class="btn btn-sm" style="color:#A32D2D"><i class="ti ti-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:var(--color-text-tertiary);padding:2rem">لا توجد منتجات</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
