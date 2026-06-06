@extends('layouts.app')
@section('title', 'تعديل الصالة')
@section('content')
<div class="page-title">تعديل الصالة: {{ $gym->name }}</div>
<div class="card" style="max-width:600px">
    <form method="POST" action="{{ route('admin.gyms.update', $gym->id) }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>اسم الصالة</label><input name="name" value="{{ old('name', $gym->name) }}" required></div>
            <div class="input-grp"><label>المدينة</label><input name="city" value="{{ old('city', $gym->city) }}" required></div>
            <div class="input-grp"><label>العنوان</label><input name="address" value="{{ old('address', $gym->address) }}" required></div>
            <div class="input-grp"><label>رقم الهاتف</label><input name="phone" value="{{ old('phone', $gym->phone) }}"></div>
            <div class="input-grp"><label>السعة</label><input name="capacity" type="number" value="{{ old('capacity', $gym->capacity) }}"></div>
            <div class="input-grp"><label>عدد المدربين</label><input name="trainer_count" type="number" value="{{ old('trainer_count', $gym->trainer_count) }}"></div>
            <div class="input-grp"><label>رابط الصورة</label><input name="image" value="{{ old('image', $gym->image) }}" placeholder="storage/app/public/..."></div>
            <div class="input-grp">
                <label>الحالة</label>
                <select name="status" style="width:100%;padding:8px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-family:var(--font-sans)">
                    <option value="active" {{ $gym->status === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ $gym->status === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                </select>
            </div>
        </div>
        <div style="display:flex;gap:8px">
            <button class="btn btn-primary"><i class="ti ti-check"></i> حفظ التغييرات</button>
            <a href="{{ route('admin.gyms') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
