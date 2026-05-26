@extends('layouts.app')
@section('title', 'إضافة مشترك')
@section('content')
<div class="page-title">إضافة مشترك جديد</div>
<form method="POST" action="{{ route('admin.members.store') }}">
    @csrf
    <div class="card">
        <div class="card-title"><i class="ti ti-user-plus" style="color:#1D9E75"></i> البيانات الأساسية</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="input-grp"><label>الاسم</label><input name="name" required></div>
            <div class="input-grp"><label>البريد الإلكتروني</label><input name="email" type="email" required></div>
            <div class="input-grp"><label>رقم الجوال</label><input name="phone"></div>
            <div class="input-grp"><label>كلمة المرور</label><input name="password" type="password" required></div>
            <div class="input-grp"><label>الصالة</label>
                <select name="gym_id">
                    <option value="">— اختر —</option>
                    @foreach($gyms as $gym)
                    <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp"><label>الهدف</label>
                <select name="goal">
                    <option value="">— اختر —</option>
                    <option value="تخسيس">تخسيس</option>
                    <option value="تضخيم">تضخيم</option>
                    <option value="لياقة">لياقة</option>
                    <option value="صحة عامة">صحة عامة</option>
                </select>
            </div>
            <div class="input-grp"><label>الوزن الحالي (كغ)</label><input name="current_weight" type="number" step="0.1"></div>
            <div class="input-grp"><label>الطول (سم)</label><input name="height" type="number" step="0.1"></div>
            <div class="input-grp"><label>نسبة الدهون (%)</label><input name="body_fat" type="number" step="0.1"></div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> حفظ المشترك</button>
    <a href="{{ route('admin.members') }}" class="btn">إلغاء</a>
</form>
@endsection