@extends('layouts.app')
@section('title', 'واتساب')
@section('content')
<div class="page-title"><i class="ti ti-brand-whatsapp" style="color:#1D9E75"></i> ربط واتساب</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
<div class="card">
    <div class="card-title">إعدادات السيرفر المحلي</div>
    <p style="font-size:12px;color:var(--color-text-secondary);margin-bottom:1rem;line-height:1.7">
        شغّل سيرفر واتساب المحلي على جهازك، ثم أدخل الرابط والمفتاح هنا.
        <br><a href="https://github.com/rassameldeeb4-droid/fitness-platform/tree/main/whatsapp-server" target="_blank" style="color:#1D9E75"><i class="ti ti-download"></i> تحميل سيرفر واتساب من GitHub</a>
    </p>
    <form method="POST" action="{{ route('trainer.whatsapp.update') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">رابط السيرفر المحلي</label>
            <input type="url" name="server_url" value="{{ old('server_url', $config->server_url ?? 'http://localhost:3001') }}" class="form-control" placeholder="http://localhost:3001" dir="ltr">
            <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px">مثال: http://192.168.1.5:3001</div>
        </div>
        <div class="form-group">
            <label class="form-label">مفتاح API (اختياري)</label>
            <input type="text" name="api_key" value="{{ old('api_key', $config->api_key ?? '') }}" class="form-control" placeholder="اختياري" dir="ltr">
        </div>
        <div class="form-group">
            <label class="form-label">رقم الجوال (للاختبار)</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $config->phone_number ?? '') }}" class="form-control" placeholder="9665xxxxxxxx" dir="ltr">
        </div>
        <div class="form-group">
            <label class="form-label">إرسال إشعارات تلقائية</label>
            <div style="display:flex;flex-direction:column;gap:6px;margin-top:6px">
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="notify_nutrition" value="1" {{ ($config->notify_nutrition ?? true) ? 'checked' : '' }} style="width:16px;height:16px">
                    عند إضافة خطة غذائية
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="notify_workout" value="1" {{ ($config->notify_workout ?? true) ? 'checked' : '' }} style="width:16px;height:16px">
                    عند إضافة خطة تمارين
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="notify_progress" value="1" {{ ($config->notify_progress ?? false) ? 'checked' : '' }} style="width:16px;height:16px">
                    عند تسجيل تقدم جديد
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:6px"><i class="ti ti-device-floppy"></i> حفظ الإعدادات</button>
    </form>
</div>

<div class="card">
    <div class="card-title">حالة الاتصال</div>
    @if($config && $config->is_connected)
    <div style="text-align:center;padding:2rem">
        <div style="font-size:48px;color:#1D9E75;margin-bottom:10px">✅</div>
        <div style="font-size:16px;font-weight:500;color:#1D9E75">متصل</div>
        <div style="font-size:12px;color:var(--color-text-secondary);margin-top:4px">{{ $config->server_url }}</div>
    </div>
    @else
    <div style="text-align:center;padding:2rem">
        <div style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:10px">📵</div>
        <div style="font-size:16px;font-weight:500;color:var(--color-text-secondary)">غير متصل</div>
        <div style="font-size:12px;color:var(--color-text-tertiary);margin-top:4px">أدخل رابط السيرفر المحلي واحفظ الإعدادات</div>
    </div>
    @endif
    <div style="display:flex;gap:8px">
        @if($config && $config->is_connected)
        <form method="POST" action="{{ route('trainer.whatsapp.test') }}" style="flex:1">
            @csrf
            <button type="submit" class="btn" style="width:100%;text-align:center"><i class="ti ti-send"></i> إرسال رسالة اختبار</button>
        </form>
        @endif
        <a href="{{ route('trainer.trainees') }}" class="btn" style="flex:1;text-align:center"><i class="ti ti-users"></i> إرسال رسالة جماعية</a>
    </div>
</div>
</div>

<div class="card" style="margin-top:1rem">
    <div class="card-title">تعليمات التشغيل</div>
    <ol style="font-size:13px;color:var(--color-text-secondary);line-height:2;padding-right:1.5rem">
        <li>حمّل ملف <strong>سيرفر واتساب</strong> من الرابط أعلاه</li>
        <li>شغّل السيرفر على جهازك: <code dir="ltr" style="background:#f0f0f0;padding:2px 6px;border-radius:4px;font-size:12px">node server.js</code></li>
        <li>امسح QR code اللي يظهر في الطرفية باستخدام واتساب</li>
        <li>بعد الربط، أدخل رابط السيرفر في الحقل أعلاه (http://localhost:3001)</li>
        <li>احفظ الإعدادات، ثم اختبر الإرسال</li>
    </ol>
</div>
@endsection