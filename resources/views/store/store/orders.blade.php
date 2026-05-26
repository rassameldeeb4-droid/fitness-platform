@extends('layouts.app')
@section('title', 'طلباتي')
@section('content')
<div class="page-title"><i class="ti ti-receipt" style="color:#1D9E75"></i> طلباتي</div>
@forelse($orders as $o)
<div class="card" style="padding:1rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
        <div>
            <span style="font-weight:500">طلب #{{ $o->id }}</span>
            <span style="font-size:12px;color:var(--color-text-tertiary);margin-right:8px">{{ $o->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <span class="badge {{ $o->status === 'completed' ? 'badge-green' : ($o->status === 'cancelled' ? 'badge-red' : 'badge-amber') }}">{{ $o->status === 'pending' ? 'قيد الانتظار' : ($o->status === 'completed' ? 'مكتمل' : 'ملغي') }}</span>
    </div>
    <div style="font-size:12px;color:var(--color-text-secondary)">
        @foreach($o->items as $item)
        <div style="display:flex;justify-content:space-between;padding:3px 0">
            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
            <span>{{ number_format($item->price * $item->quantity, 2) }} ر.س</span>
        </div>
        @endforeach
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:6px;padding-top:6px;border-top:0.5px solid var(--color-border-tertiary);font-weight:500">
        <span>الإجمالي</span>
        <span style="color:#1D9E75">{{ number_format($o->total, 2) }} ر.س</span>
    </div>
</div>
@empty
<div class="card" style="text-align:center;padding:2rem;color:var(--color-text-tertiary)">
    <div style="font-size:48px;margin-bottom:1rem"><i class="ti ti-shopping-cart-off"></i></div>
    <div>لا توجد طلبات سابقة</div>
    <a href="{{ route('store') }}" class="btn btn-primary" style="margin-top:1rem">تصفح المنتجات</a>
</div>
@endforelse
@endsection
