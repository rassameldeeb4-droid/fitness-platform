@extends('layouts.app')
@section('title', 'تعديل المنتج')
@section('content')
<div class="page-title">تعديل المنتج</div>
<div class="card">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid-2">
            <div class="input-grp">
                <label>اسم المنتج (عربي)</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}">
            </div>
            <div class="input-grp">
                <label>اسم المنتج (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}">
            </div>
            <div class="input-grp">
                <label>العلامة التجارية</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}">
            </div>
            <div class="input-grp">
                <label>الفئة</label>
                <select name="category" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ (old('category', $product->category) === $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>السعر (ر.س)</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price', $product->price) }}">
            </div>
            <div class="input-grp">
                <label>السعر القديم (اختياري)</label>
                <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $product->old_price) }}">
            </div>
            <div class="input-grp">
                <label>الوسم (badge)</label>
                <input type="text" name="badge" placeholder="مثل: خصم 20%" value="{{ old('badge', $product->badge) }}">
            </div>
            <div class="input-grp">
                <label>صورة المنتج</label>
                <input type="file" name="image" accept="image/*">
                @if($product->image)<div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px"><img src="{{ Storage::url($product->image) }}" style="width:48px;height:48px;border-radius:6px;object-fit:cover;vertical-align:middle;margin-left:6px"> الصورة الحالية</div>@endif
            </div>
        </div>
        <div class="input-grp" style="margin-top:12px">
            <label>الوصف</label>
            <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="input-grp" style="margin-top:12px">
            <label>المميزات (سطر لكل ميزة)</label>
            <textarea name="features" rows="4">{{ old('features', $product->features ? implode("\n", $product->features) : '') }}</textarea>
        </div>
        <div style="margin-top:12px;display:flex;gap:12px;align-items:center">
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}> نشط
            </label>
        </div>
        <div style="margin-top:1rem">
            <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> حفظ التغييرات</button>
            <a href="{{ route('admin.products.index') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
