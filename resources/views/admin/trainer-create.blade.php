@extends('layouts.app')
@section('title', 'إضافة مدرب')
@section('content')
<div class="page-title">إضافة مدرب جديد</div>
<div class="card" style="max-width:500px">
    <form method="POST" action="{{ route('admin.trainers.store') }}">
        @csrf
        <div style="display:grid;gap:12px">
            <div class="input-grp"><label>الاسم</label><input type="text" name="name" value="{{ old('name') }}" required>@error('name')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>البريد</label><input type="email" name="email" value="{{ old('email') }}" required>@error('email')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>الجوال</label><input type="text" name="phone" value="{{ old('phone') }}"></div>
            <div class="input-grp"><label>كلمة المرور</label><input type="password" name="password" required>@error('password')<div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div>@enderror</div>
            <div class="input-grp"><label>التخصص</label><input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="مثال: تمارين قوة"></div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">إضافة</button>
            <a href="{{ route('admin.trainers') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
