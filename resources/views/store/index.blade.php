@extends('layouts.app')
@section('title', 'المتجر')
@push('styles')
<style>
.product-card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden}
.product-img{height:120px;display:flex;align-items:center;justify-content:center;font-size:40px;position:relative}
.product-body{padding:10px 12px}
.product-name{font-size:13px;font-weight:500}
.product-brand{font-size:11px;color:var(--color-text-secondary);margin-bottom:4px}
.product-price{font-size:16px;font-weight:500;color:#1D9E75}
.product-old{font-size:12px;color:var(--color-text-tertiary);text-decoration:line-through;margin-right:4px}
</style>
@endpush
@section('content')
<div class="page-title"><i class="ti ti-shopping-bag" style="color:#1D9E75"></i> المتجر</div>
<div style="display:flex;gap:8px;margin-bottom:1rem">
    <input placeholder="ابحث في المتجر..." style="flex:1;padding:8px 12px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;font-family:var(--font-sans);outline:none">
</div>
<div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:1rem">
    @foreach($categories as $i => $cat)
    <span class="cat-pill {{ $i === 0 ? 'active' : '' }}">{{ $cat }}</span>
    @endforeach
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:10px">
    @forelse($products as $p)
    <div class="product-card">
        <div class="product-img" style="background:#E1F5EE">
            @if($p->badge)<div style="position:absolute;top:8px;right:8px;background:#1D9E75;color:#fff;font-size:10px;padding:2px 8px;border-radius:10px">{{ $p->badge }}</div>@endif
            <span style="font-size:36px">🏋️</span>
        </div>
        <div class="product-body">
            <div class="product-brand">{{ $p->brand ?? 'ماركة' }}</div>
            <div class="product-name">{{ $p->name }}</div>
            <div style="color:#854F0B;font-size:12px;margin:3px 0">{{ str_repeat('★', round($p->rating ?? 0)) }} {{ $p->rating ?? 0 }}</div>
            <div style="display:flex;align-items:center;margin:6px 0 8px">
                <span class="product-price">{{ $p->price }} ر</span>
                @if($p->old_price)<span class="product-old">{{ $p->old_price }} ر</span>@endif
            </div>
            <button class="btn btn-primary" style="width:100%;font-size:12px;padding:6px"><i class="ti ti-shopping-cart"></i> أضف للسلة</button>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--color-text-secondary)">المتجر قيد الإعداد</div>
    @endforelse
</div>
@endsection
