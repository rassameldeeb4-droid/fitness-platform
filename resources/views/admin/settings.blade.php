@extends('layouts.app')
@section('title', 'الإعدادات')
@section('content')
<div class="page-title">إعدادات النظام</div>

@if(session('success'))<div style="background:#E1F5EE;color:#0F6E56;padding:10px 16px;border-radius:8px;margin-bottom:1rem;font-size:14px">{{ session('success') }}</div>@endif

<div class="card">
    <div class="card-title">معلومات المنصة</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>اسم المنصة</label><input name="site_name" value="{{ old('site_name', $settings['site_name']) }}"></div>
            <div class="input-grp"><label>البريد الإلكتروني</label><input name="site_email" value="{{ old('site_email', $settings['site_email']) }}"></div>
        </div>
        <button class="btn btn-primary">حفظ الإعدادات</button>
    </form>
</div>

<div class="card">
    <div class="card-title">إشعارات الاشتراكات</div>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @php
            $notifs = [
                ['key' => 'notif_expiry_7', 'label' => 'إشعار قبل انتهاء الاشتراك بـ 7 أيام'],
                ['key' => 'notif_auto_renew', 'label' => 'إشعار تجديد تلقائي'],
                ['key' => 'notif_late_payment', 'label' => 'إشعار المدفوعات المتأخرة'],
            ];
        @endphp
        @foreach($notifs as $n)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
            <span style="font-size:13px">{{ $n['label'] }}</span>
            <label style="width:36px;height:20px;background:{{ ($settings[$n['key']] ?? '0') === '1' ? '#1D9E75' : '#ccc' }};border-radius:10px;cursor:pointer;position:relative;transition:background 0.2s">
                <input type="checkbox" name="{{ $n['key'] }}" value="1" {{ ($settings[$n['key']] ?? '0') === '1' ? 'checked' : '' }} onchange="this.parentElement.style.background=this.checked?'#1D9E75':'#ccc'" style="opacity:0;width:0;height:0">
                <div style="width:16px;height:16px;background:white;border-radius:50%;position:absolute;top:2px;{{ ($settings[$n['key']] ?? '0') === '1' ? 'right:18px' : 'right:2px' }};transition:right 0.2s"></div>
            </label>
        </div>
        @endforeach
        <div style="margin-top:12px"><button class="btn btn-primary">حفظ الإعدادات</button></div>
    </form>
</div>

<script>
document.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var dot = this.parentElement.querySelector('div');
        dot.style.right = this.checked ? '18px' : '2px';
    });
});
</script>
@endsection