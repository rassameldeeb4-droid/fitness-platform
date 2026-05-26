@extends('layouts.app')
@section('title', 'إتمام الطلب')
@section('content')
<div class="page-title"><i class="ti ti-credit-card" style="color:#1D9E75"></i> إتمام الطلب</div>
<div class="grid-2">
    <div class="card">
        <div class="card-title"><i class="ti ti-shopping-bag"></i> ملخص الطلب</div>
        @foreach($cart as $item)
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:13px">
            <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
            <span>{{ number_format($item['price'] * $item['quantity'], 2) }} ر.س</span>
        </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-weight:500;font-size:15px">
            <span>الإجمالي</span>
            <span style="color:#1D9E75">{{ number_format($total, 2) }} ر.س</span>
        </div>
    </div>
    <div class="card">
        <div class="card-title"><i class="ti ti-truck-delivery"></i> معلومات الطلب</div>
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="input-grp" style="margin-bottom:12px">
                <label>طريقة الدفع</label>
                <select name="payment_method" required>
                    <option value="">اختر طريقة الدفع</option>
                    <option value="cash">الدفع عند الاستلام</option>
                    <option value="card">بطاقة ائتمان</option>
                    <option value="bank">تحويل بنكي</option>
                </select>
            </div>
            <div class="input-grp" style="margin-bottom:12px">
                <label>ملاحظات (اختياري)</label>
                <textarea name="notes" rows="3" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%"><i class="ti ti-check"></i> تأكيد الطلب</button>
        </form>
    </div>
</div>
@endsection
