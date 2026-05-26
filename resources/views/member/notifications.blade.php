@extends('layouts.app')
@section('title', 'تنبيهاتي')
@section('content')
<div class="page-title">تنبيهاتي</div>

<div class="card" style="border-color:#25D366;margin-bottom:1rem">
    <div style="display:flex;align-items:center;gap:8px">
        <div style="width:36px;height:36px;border-radius:50%;background:#E4FBE9;display:flex;align-items:center;justify-content:center">
            <i class="ti ti-brand-whatsapp" style="color:#25D366;font-size:20px"></i>
        </div>
        <div><div style="font-size:14px;font-weight:500">تنبيهات واتس آب</div>
        <div style="font-size:12px;color:#25D366">{{ $settings->whatsapp_enabled ? 'مفعّلة ✓' : 'غير مفعّلة' }}</div></div>
    </div>
</div>

<div class="card" style="margin-bottom:1rem">
    <div class="card-title"><i class="ti ti-droplet" style="color:#185FA5"></i> تنبيهات شرب الماء</div>
    @php $waterTimes = ['7:00 ص','9:00 ص','11:00 ص','1:00 م','3:00 م','5:00 م','7:00 م','9:00 م']; @endphp
    @foreach($waterTimes as $i => $wt)
    <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <span style="font-size:12px;color:#185FA5;font-weight:500;min-width:42px">{{ $wt }}</span>
        <span style="font-size:12px;color:var(--color-text-primary)">حان وقت كوب الماء {{ $i+1 }}</span>
        @if($i == 2)<span class="badge badge-blue" style="font-size:10px;margin-right:auto">الآن</span>@endif
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-title"><i class="ti ti-bell" style="color:#534AB7"></i> تحكم بالتنبيهات</div>
    <form method="POST" action="{{ route('member.notifications.update') }}">
        @csrf
        @php
        $toggles = [
            ['name'=>'workout_reminder','label'=>'تذكير موعد التمرين','sub'=>'قبل 30 دقيقة من الجلسة','icon'=>'ti-barbell','color'=>'#534AB7'],
            ['name'=>'meal_reminder','label'=>'تنبيهات الوجبات','sub'=>'قبل كل وجبة بـ 15 دقيقة','icon'=>'ti-salad','color'=>'#1D9E75'],
            ['name'=>'water_reminder','label'=>'تذكير شرب الماء','sub'=>'كل ساعتين خلال اليوم','icon'=>'ti-droplet','color'=>'#185FA5'],
            ['name'=>'sleep_reminder','label'=>'تذكير النوم المبكر','sub'=>'الساعة 10:30 مساءً','icon'=>'ti-moon','color'=>'#854F0B'],
            ['name'=>'measurement_reminder','label'=>'قياس الوزن الأسبوعي','sub'=>'السبت 7:00 صباحاً','icon'=>'ti-scale','color'=>'#185FA5'],
            ['name'=>'supplement_reminder','label'=>'المكملات الغذائية','sub'=>'صباحاً ومساءً مع الوجبات','icon'=>'ti-pill','color'=>'#993556'],
        ];
        @endphp
        @foreach($toggles as $t)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
            <div style="width:38px;height:38px;border-radius:50%;background:#E1F5EE;display:flex;align-items:center;justify-content:center">
                <i class="ti {{ $t['icon'] }}" style="color:{{ $t['color'] }};font-size:18px"></i>
            </div>
            <div style="flex:1"><div style="font-size:13px;font-weight:500">{{ $t['label'] }}</div>
            <div style="font-size:11px;color:var(--color-text-secondary)">{{ $t['sub'] }}</div></div>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                <input type="hidden" name="{{ $t['name'] }}" value="0">
                <input type="checkbox" name="{{ $t['name'] }}" value="1" {{ $settings->{$t['name']} ? 'checked' : '' }} style="width:18px;height:18px;accent-color:#1D9E75">
            </label>
        </div>
        @endforeach
        <div style="margin-top:1rem">
            <div class="input-grp" style="margin-bottom:8px"><label>رقم الواتس آب (للتنبيهات)</label><input name="whatsapp_phone" value="{{ $settings->whatsapp_phone }}" placeholder="+966XXXXXXXXX"></div>
            <button class="btn btn-primary"><i class="ti ti-check"></i> حفظ الإعدادات</button>
        </div>
    </form>
</div>
@endsection
