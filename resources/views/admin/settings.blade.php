@extends('layouts.app')
@section('title', 'الإعدادات')
@section('content')
<div class="page-title">إعدادات النظام</div>
<div class="card">
    <div class="card-title">معلومات المنصة</div>
    <form method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>اسم المنصة</label><input value="Fitness Platform" name="name"></div>
            <div class="input-grp"><label>البريد الإلكتروني</label><input value="admin@fitness.com" name="email"></div>
        </div>
        <button class="btn btn-primary">حفظ الإعدادات</button>
    </form>
</div>
<div class="card">
    <div class="card-title">إشعارات الاشتراكات</div>
    @php $notifs = ['إشعار قبل انتهاء الاشتراك بـ 7 أيام','إشعار تجديد تلقائي','إشعار المدفوعات المتأخرة']; @endphp
    @foreach($notifs as $n)
    <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <span style="font-size:13px">{{ $n }}</span>
        <div style="width:36px;height:20px;background:#1D9E75;border-radius:10px;cursor:pointer;position:relative"><div style="width:16px;height:16px;background:white;border-radius:50%;position:absolute;right:2px;top:2px"></div></div>
    </div>
    @endforeach
</div>
@endsection
