@extends('layouts.app')
@section('title', 'تعديل طبيب')
@section('content')
<div class="page-title">تعديل بيانات الطبيب</div>
<div class="card" style="max-width:500px">
    <form method="POST" action="{{ route('admin.doctors.update', $doctor->id) }}">
        @csrf @method('PUT')
        <div style="display:grid;gap:12px">
            <div class="input-grp">
                <label>الاسم</label>
                <input type="text" name="name" value="{{ old('name', $doctor->name) }}" required>
                @error('name') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required>
                @error('email') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>رقم الجوال</label>
                <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}">
            </div>
            <div class="input-grp">
                <label>كلمة المرور (اتركها فارغة إن لم ترد التغيير)</label>
                <input type="password" name="password">
                @error('password') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.doctors') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
