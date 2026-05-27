@extends('layouts.app')
@section('title', 'إضافة تمرين')
@section('content')
<div class="page-title">إضافة تمرين</div>
<div class="card">
    <form method="POST" action="{{ route('admin.exercises.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-2">
            <div class="input-grp">
                <label>اسم التمرين (عربي)</label>
                <input type="text" name="name" required value="{{ old('name') }}">
            </div>
            <div class="input-grp">
                <label>اسم التمرين (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en') }}">
            </div>
            <div class="input-grp">
                <label>الفئة</label>
                <select name="category" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>العضلة المستهدفة</label>
                <input type="text" name="muscle_group" required value="{{ old('muscle_group') }}">
            </div>
            <div class="input-grp">
                <label>عدد المجموعات</label>
                <input type="number" name="sets_default" required value="{{ old('sets_default', 3) }}">
            </div>
            <div class="input-grp">
                <label>العدات</label>
                <input type="text" name="reps_default" required value="{{ old('reps_default', '12') }}">
            </div>
            <div class="input-grp">
                <label>الصعوبة</label>
                <select name="difficulty" required>
                    @foreach($difficulties as $d)
                    <option value="{{ $d }}" {{ old('difficulty') === $d ? 'selected' : '' }}>{{ $d === 'beginner' ? 'مبتدئ' : ($d === 'intermediate' ? 'متوسط' : 'متقدم') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>الأجهزة المطلوبة</label>
                <input type="text" name="equipment" value="{{ old('equipment') }}">
            </div>
            <div class="input-grp">
                <label>حرق السعرات (لكل مجموعة)</label>
                <input type="number" name="calories_per_set" value="{{ old('calories_per_set') }}">
            </div>
            <div class="input-grp">
                <label>رابط فيديو خارجي (YouTube)</label>
                <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
            </div>
            <div class="input-grp">
                <label>رفع فيديو</label>
                <input type="file" name="video" accept="video/*">
            </div>
            <div class="input-grp">
                <label>صورة توضيحية</label>
                <input type="file" name="image" accept="image/*">
            </div>
        </div>
        <div class="input-grp" style="margin-top:12px">
            <label>الوصف</label>
            <textarea name="description" rows="4">{{ old('description') }}</textarea>
        </div>
        <div style="margin-top:12px;display:flex;gap:12px;align-items:center">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" checked> نشط
            </label>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary" id="submitBtn"><i class="ti ti-check"></i> حفظ</button>
            <a href="{{ route('admin.exercises.index') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
<div id="loadingOverlay" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:999;align-items:center;justify-content:center">
    <div style="background:var(--color-background-primary);padding:2rem;border-radius:var(--border-radius-lg);text-align:center">
        <div style="width:40px;height:40px;border:3px solid #534AB7;border-top-color:transparent;border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 1rem"></div>
        <div style="font-size:14px;color:var(--color-text-secondary)">جاري حفظ التمرين...</div>
    </div>
</div>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
<script>document.querySelector('form').addEventListener('submit',function(){document.getElementById('loadingOverlay').style.display='flex';document.getElementById('submitBtn').disabled=true;});</script>
@endsection
