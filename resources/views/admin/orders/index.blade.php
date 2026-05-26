@extends('layouts.app')
@section('title', 'الطلبات')
@section('content')
<div class="page-title">الطلبات</div>
<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>عدد المنتجات</th>
                <th>الإجمالي</th>
                <th>طريقة الدفع</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $o)
            <tr>
                <td style="color:var(--color-text-tertiary)">#{{ $o->id }}</td>
                <td>{{ $o->user->name }}</td>
                <td>{{ $o->items->sum('quantity') }}</td>
                <td><strong>{{ number_format($o->total, 2) }} ر.س</strong></td>
                <td>{{ $o->payment_method ?? '—' }}</td>
                <td><span class="badge {{ $o->status === 'completed' ? 'badge-green' : ($o->status === 'cancelled' ? 'badge-red' : 'badge-amber') }}">{{ $o->status === 'pending' ? 'قيد الانتظار' : ($o->status === 'completed' ? 'مكتمل' : 'ملغي') }}</span></td>
                <td style="color:var(--color-text-tertiary);font-size:11px">{{ $o->created_at->format('Y-m-d') }}</td>
                <td><a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm"><i class="ti ti-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:var(--color-text-tertiary);padding:2rem">لا توجد طلبات</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
