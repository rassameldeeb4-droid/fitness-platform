@extends('layouts.app')
@section('title', 'تعديل باقة')
@section('content')
<div class="page-title">تعديل الباقة</div>
<div class="card" style="max-width:600px">
    <form method="POST" action="{{ route('admin.packages.update', $package->id) }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <div class="input-grp"><label>الاسم</label><input type="text" name="name" value="{{ old('name', $package->name) }}" required></div>
            <div class="input-grp"><label>السعر (ريال)</label><input type="number" step="0.01" name="price" value="{{ old('price', $package->price) }}" required></div>
            <div class="input-grp"><label>المدة (أيام)</label><input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required></div>
            <div class="input-grp"><label>النوع</label>
                <select name="type">
                    <option value="monthly" {{ $package->type === 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ $package->type === 'yearly' ? 'selected' : '' }}>سنوي</option>
                    <option value="lifetime" {{ $package->type === 'lifetime' ? 'selected' : '' }}>مدى الحياة</option>
                </select>
            </div>
            <div class="input-grp" style="grid-column:1/-1"><label>الوصف</label><textarea name="description" rows="2">{{ old('description', $package->description) }}</textarea></div>
            <div class="input-grp"><label>الوسم</label><input type="text" name="badge" value="{{ old('badge', $package->badge) }}" placeholder="مثال: الأكثر شيوعاً"></div>
            <div class="input-grp"><label>الحد الأقصى للحجوزات</label><input type="number" name="max_bookings" value="{{ old('max_bookings', $package->max_bookings) }}"></div>
            <div class="input-grp" style="grid-column:1/-1"><label>المميزات (سطر لكل ميزة)</label><textarea name="features" rows="4">{{ old('features', is_array($package->features) ? implode("\n", $package->features) : $package->features) }}</textarea></div>
        </div>
        <div style="display:flex;gap:16px;margin:10px 0">
            <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_trainer" value="1" {{ $package->has_trainer ? 'checked' : '' }}> مدرب شخصي</label>
            <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_nutrition" value="1" {{ $package->has_nutrition ? 'checked' : '' }}> نظام غذائي</label>
            <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_ai" value="1" {{ $package->has_ai ? 'checked' : '' }}> ذكاء اصطناعي</label>
            <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}> نشط</label>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.packages') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection