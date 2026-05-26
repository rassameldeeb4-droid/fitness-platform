@extends('layouts.app')
@section('title', 'الباقات')
@section('content')
<div class="page-title">إدارة الباقات</div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:1.5rem">
    @php
        $packageData = [
            ['name'=>'مجانية','price'=>'0','color'=>'#888780','clients'=>234,'features'=>['وصول أساسي','تطبيق الجوال','3 تمارين/أسبوع']],
            ['name'=>'شهرية','price'=>'149','color'=>'#185FA5','clients'=>487,'features'=>['كل مميزات المجانية','مدرب شخصي','نظام غذائي','متابعة يومية']],
            ['name'=>'بريميوم سنوية','price'=>'999','color'=>'#1D9E75','clients'=>563,'features'=>['كل المميزات','أولوية الحجز','تحليل متقدم','تقارير شهرية'],'popular'=>true],
        ];
    @endphp
    @foreach($packageData as $p)
    <div class="card" style="border-color:{{ $p['color'] }};text-align:center">
        @if(isset($p['popular']))<div style="background:#E1F5EE;color:#0F6E56;font-size:11px;padding:4px;border-radius:6px;margin-bottom:8px">الأكثر شيوعاً</div>@endif
        <div style="font-size:16px;font-weight:500;margin-bottom:4px">{{ $p['name'] }}</div>
        <div style="font-size:28px;font-weight:500;color:{{ $p['color'] }};margin-bottom:4px">{{ $p['price'] }} <span style="font-size:14px">ريال</span></div>
        <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem">{{ $p['clients'] }} عميل</div>
        @foreach($p['features'] as $f)
        <div style="font-size:12px;color:var(--color-text-secondary);padding:3px 0;display:flex;align-items:center;gap:6px;justify-content:center"><i class="ti ti-check" style="color:#1D9E75"></i>{{ $f }}</div>
        @endforeach
    </div>
    @endforeach
</div>
@endsection
