@extends('layouts.app')
@section('title', 'تعديل مدرب')
@section('content')
<div class="page-title">تعديل بيانات المدرب</div>
<div class="card" style="max-width:500px">
    <form method="POST" action="{{ route('admin.trainers.update', $trainer->id) }}">
        @csrf @method('PUT')
        <div style="display:grid;gap:12px">
            <div class="input-grp"><label>الاسم</label><input type="text" name="name" value="{{ old('name', $trainer->name) }}" required>@error('name')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>البريد</label><input type="email" name="email" value="{{ old('email', $trainer->email) }}" required>@error('email')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>الجوال</label><input type="text" name="phone" value="{{ old('phone', $trainer->phone) }}"></div>
            <div class="input-grp"><label>كلمة المرور (اتركها فارغة إن لم ترد التغيير)</label><input type="password" name="password">@error('password')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>التخصص</label><input type="text" name="specialty" value="{{ old('specialty', $trainer->trainerProfile->specialty ?? '') }}"></div>
            <div class="input-grp"><label><input type="checkbox" name="available" value="1" {{ ($trainer->trainerProfile->available ?? false) ? 'checked' : '' }}> متاح للعمل</label></div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">حفظ</button>
            <a href="{{ route('admin.trainers') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
