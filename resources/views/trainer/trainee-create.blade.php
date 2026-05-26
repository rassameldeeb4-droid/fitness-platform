@extends('layouts.app')
@section('title', 'إضافة متدرب جديد')
@section('content')
<div class="page-title">إضافة متدرب جديد</div>
<div class="card" style="max-width:500px">
    <form method="POST" action="{{ route('trainer.trainees.store') }}" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;gap:12px">
            <div class="input-grp">
                <label>الاسم</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>رقم الجوال</label>
                <input type="text" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="input-grp">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
                @error('password') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>الصورة الشخصية</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                @error('image') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>الهدف</label>
                <select name="goal">
                    <option value="">— اختر —</option>
                    <option value="تضخيم" {{ old('goal') == 'تضخيم' ? 'selected' : '' }}>تضخيم</option>
                    <option value="تنشيف" {{ old('goal') == 'تنشيف' ? 'selected' : '' }}>تنشيف</option>
                    <option value="بناء عضلات" {{ old('goal') == 'بناء عضلات' ? 'selected' : '' }}>بناء عضلات</option>
                    <option value="شد عضلات" {{ old('goal') == 'شد عضلات' ? 'selected' : '' }}>شد عضلات</option>
                    <option value="تخسيس" {{ old('goal') == 'تخسيس' ? 'selected' : '' }}>تخسيس</option>
                    <option value="لياقة" {{ old('goal') == 'لياقة' ? 'selected' : '' }}>لياقة عامة</option>
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                <div class="input-grp">
                    <label>الوزن (كغ)</label>
                    <input type="number" name="current_weight" step="0.1" min="0" value="{{ old('current_weight') }}">
                </div>
                <div class="input-grp">
                    <label>الطول (سم)</label>
                    <input type="number" name="height" step="0.1" min="0" value="{{ old('height') }}">
                </div>
                <div class="input-grp">
                    <label>الدهون %</label>
                    <input type="number" name="body_fat" step="0.1" min="0" value="{{ old('body_fat') }}">
                </div>
            </div>
            <div class="input-grp">
                <label>الإصابات إن وجدت</label>
                <textarea name="injuries" rows="3" placeholder="مثل: إصابة في الركبة، تمزق في الكتف...">{{ old('injuries') }}</textarea>
            </div>
            <div class="input-grp">
                <label>الشكاوي والملاحظات الطبية</label>
                <textarea name="complaints" rows="3" placeholder="مثل: آلام أسفل الظهر، ضيق تنفس أثناء التمرين...">{{ old('complaints') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">إضافة المتدرب</button>
            <a href="{{ route('trainer.trainees') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection