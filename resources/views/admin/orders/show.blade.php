@extends('layouts.app')
@section('title', 'تفاصيل الطلب #' . $order->id)
@section('content')
<a href="{{ route('admin.orders.index') }}" class="btn btn-sm" style="margin-bottom:1rem"><i class="ti ti-arrow-right"></i> العودة للطلبات</a>
<div class="page-title">تفاصيل الطلب #{{ $order->id }}</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:1rem">
    <div class="card">
        <div class="card-title"><i class="ti ti-user"></i> معلومات العميل</div>
        <div style="font-size:13px;line-height:1.8">
            <div><strong>الاسم:</strong> {{ $order->user->name }}</div>
            <div><strong>البريد:</strong> {{ $order->user->email }}</div>
        </div>
    </div>
    <div class="card">
        <div class="card-title"><i class="ti ti-info-circle"></i> معلومات الطلب</div>
        <div style="font-size:13px;line-height:1.8">
            <div><strong>الحالة:</strong> <span class="badge {{ $order->status === 'completed' ? 'badge-green' : ($order->status === 'cancelled' ? 'badge-red' : 'badge-amber') }}">{{ $order->status === 'pending' ? 'قيد الانتظار' : ($order->status === 'completed' ? 'مكتمل' : 'ملغي') }}</span></div>
            <div><strong>طريقة الدفع:</strong> {{ $order->payment_method ?? '—' }}</div>
            <div><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
            @if($order->notes)<div><strong>ملاحظات:</strong> {{ $order->notes }}</div>@endif
        </div>
    </div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-shopping-bag"></i> المنتجات</div>
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>المجموع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->price, 2) }} ر.س</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }} ر.س</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:left;font-weight:500">الإجمالي</td>
                <td style="font-weight:500;color:#1D9E75">{{ number_format($order->total, 2) }} ر.س</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
