@extends('layouts.app')
@section('title', 'سلة التسوق')
@section('content')
<div class="page-title"><i class="ti ti-shopping-cart" style="color:#1D9E75"></i> سلة التسوق</div>

@if(empty($cart))
<div class="card" style="text-align:center;padding:2rem">
    <div style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem"><i class="ti ti-shopping-cart-off"></i></div>
    <div style="font-size:15px;color:var(--color-text-secondary);margin-bottom:1rem">السلة فارغة</div>
    <a href="{{ route('store') }}" class="btn btn-primary"><i class="ti ti-arrow-right"></i> تصفح المنتجات</a>
</div>
@else
<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>المنتج</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>المجموع</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $id => $item)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        @if($item['image'])
                        <img src="{{ Storage::url($item['image']) }}" alt="" style="width:36px;height:36px;border-radius:6px;object-fit:cover">
                        @endif
                        <span>{{ $item['name'] }}</span>
                    </div>
                </td>
                <td>{{ number_format($item['price'], 2) }} ر.س</td>
                <td>
                    <form method="POST" action="{{ route('cart.update') }}" style="display:flex;align-items:center;gap:4px">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:50px;padding:4px 6px;border-radius:4px;border:0.5px solid var(--color-border-secondary);font-size:13px;text-align:center">
                        <button type="submit" class="btn btn-sm"><i class="ti ti-refresh"></i></button>
                    </form>
                </td>
                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} ر.س</td>
                <td>
                    <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm" style="color:#A32D2D"><i class="ti ti-trash"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:left;font-weight:500">الإجمالي</td>
                <td style="font-weight:500;color:#1D9E75;font-size:16px">{{ number_format($total, 2) }} ر.س</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>
<div style="display:flex;gap:8px;justify-content:space-between">
    <a href="{{ route('store') }}" class="btn"><i class="ti ti-arrow-right"></i> متابعة التسوق</a>
    <a href="{{ route('checkout') }}" class="btn btn-primary"><i class="ti ti-credit-card"></i> إتمام الطلب</a>
</div>
@endif
@endsection
