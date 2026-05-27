@extends('layouts.app')
@section('title', 'تعديل التمرين')
@section('content')
<div class="page-title">تعديل التمرين</div>
<div class="card">
    <form method="POST" action="{{ route('admin.exercises.update', $exercise) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid-2">
            <div class="input-grp">
                <label>اسم التمرين (عربي)</label>
                <input type="text" name="name" required value="{{ old('name', $exercise->name) }}">
            </div>
            <div class="input-grp">
                <label>اسم التمرين (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en', $exercise->name_en) }}">
            </div>
            <div class="input-grp">
                <label>الفئة</label>
                <select name="category" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ (old('category', $exercise->category) === $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>العضلة المستهدفة</label>
                <input type="text" name="muscle_group" required value="{{ old('muscle_group', $exercise->muscle_group) }}">
            </div>
            <div class="input-grp">
                <label>عدد المجموعات</label>
                <input type="number" name="sets_default" required value="{{ old('sets_default', $exercise->sets_default) }}">
            </div>
            <div class="input-grp">
                <label>العدات</label>
                <input type="text" name="reps_default" required value="{{ old('reps_default', $exercise->reps_default) }}">
            </div>
            <div class="input-grp">
                <label>الصعوبة</label>
                <select name="difficulty" required>
                    @foreach($difficulties as $d)
                    <option value="{{ $d }}" {{ (old('difficulty', $exercise->difficulty) === $d) ? 'selected' : '' }}>{{ $d === 'beginner' ? 'مبتدئ' : ($d === 'intermediate' ? 'متوسط' : 'متقدم') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>الأجهزة المطلوبة</label>
                <input type="text" name="equipment" value="{{ old('equipment', $exercise->equipment) }}">
            </div>
            <div class="input-grp">
                <label>حرق السعرات (لكل مجموعة)</label>
                <input type="number" name="calories_per_set" value="{{ old('calories_per_set', $exercise->calories_per_set) }}">
            </div>
            <div class="input-grp">
                <label>رابط فيديو خارجي (YouTube)</label>
                <input type="url" name="video_url" value="{{ old('video_url', $exercise->video_url) }}" placeholder="https://youtube.com/...">
            </div>
            <div class="input-grp">
                <label>رفع فيديو</label>
                <input type="file" name="video" accept="video/*">
                @if($exercise->video_url)
                <div style="margin-top:8px;padding:8px;background:#F5F5F5;border-radius:8px;display:flex;align-items:center;gap:8px">
                    @if(filter_var($exercise->video_url, FILTER_VALIDATE_URL))
                    <a href="{{ $exercise->video_url }}" target="_blank" style="flex:1;font-size:12px;color:#185FA5"><i class="ti ti-video"></i> فتح الفيديو الخارجي</a>
                    @else
                    <video controls style="width:160px;height:90px;border-radius:6px;object-fit:cover">
                        <source src="{{ asset('storage/' . $exercise->video_url) }}" type="video/mp4">
                    </video>
                    <span style="flex:1;font-size:12px;color:var(--color-text-secondary)">فيديو مرفوع</span>
                    @endif
                    <a href="{{ route('admin.exercises.delete-video', $exercise) }}" class="btn" style="padding:4px 8px;font-size:11px;color:#A32D2D" onclick="return confirm('حذف الفيديو؟')"><i class="ti ti-trash"></i></a>
                </div>
                @endif
            </div>
            <div class="input-grp">
                <label>صورة توضيحية</label>
                <input type="file" name="image" accept="image/*">
                @if($exercise->image)
                <div style="margin-top:8px;padding:8px;background:#F5F5F5;border-radius:8px;display:flex;align-items:center;gap:8px">
                    <img src="{{ asset('storage/' . $exercise->image) }}" style="width:80px;height:80px;border-radius:6px;object-fit:cover">
                    <span style="flex:1;font-size:12px;color:var(--color-text-secondary)">الصورة الحالية</span>
                    <a href="{{ route('admin.exercises.delete-image', $exercise) }}" class="btn" style="padding:4px 8px;font-size:11px;color:#A32D2D" onclick="return confirm('حذف الصورة؟')"><i class="ti ti-trash"></i></a>
                </div>
                @endif
            </div>
        </div>
        <div class="input-grp" style="margin-top:12px">
            <label>الوصف</label>
            <textarea name="description" rows="4">{{ old('description', $exercise->description) }}</textarea>
        </div>
        <div style="margin-top:12px;display:flex;gap:12px;align-items:center">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ $exercise->is_active ? 'checked' : '' }}> نشط
            </label>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> حفظ التغييرات</button>
            <a href="{{ route('admin.exercises.index') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
