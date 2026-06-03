@extends('layouts.app')
@section('title', 'الباقات')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">إدارة الباقات</div>
    <button onclick="toggleForm()" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة باقة</button>
</div>
<div class="stat-grid" style="grid-template-columns:repeat(1,1fr);max-width:300px">
    <div class="stat-card"><div class="stat-label"><i class="ti ti-credit-card" style="color:#1D9E75"></i> إجمالي الباقات</div><div class="stat-value" style="color:#1D9E75">{{ $packages->count() }}</div></div>
</div>

@if(session('success'))<div style="background:#E1F5EE;color:#0F6E56;padding:10px 16px;border-radius:8px;margin-bottom:1rem;font-size:14px">{{ session('success') }}</div>@endif

<div id="createForm" style="display:none;margin-bottom:1.5rem">
    <div class="card">
        <div style="font-weight:500;margin-bottom:12px;font-size:15px">إضافة باقة جديدة</div>
        <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="input-grp"><label>الاسم</label><input type="text" name="name" required></div>
                <div class="input-grp"><label>السعر (ريال)</label><input type="number" step="0.01" name="price" required></div>
                <div class="input-grp"><label>المدة (أيام)</label><input type="number" name="duration_days" required></div>
                <div class="input-grp"><label>النوع</label>
                    <select name="type">
                        <option value="monthly">شهري</option>
                        <option value="yearly">سنوي</option>
                        <option value="lifetime">مدى الحياة</option>
                    </select>
                </div>
                <div class="input-grp"><label>الوصف</label><textarea name="description" rows="2"></textarea></div>
                <div class="input-grp"><label>الوسم</label><input type="text" name="badge" placeholder="مثال: الأكثر شيوعاً"></div>
                <div class="input-grp"><label>المميزات (سطر لكل ميزة)</label><textarea name="features" rows="3" placeholder="مدرب شخصي&#10;نظام غذائي&#10;متابعة يومية"></textarea></div>
                <div class="input-grp"><label>الحد الأقصى للحجوزات</label><input type="number" name="max_bookings" value="0"></div>
            </div>
            <div style="display:flex;gap:16px;margin:10px 0">
                <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_trainer" value="1"> مدرب شخصي</label>
                <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_nutrition" value="1"> نظام غذائي</label>
                <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="has_ai" value="1"> ذكاء اصطناعي</label>
                <label style="display:flex;align-items:center;gap:4px;font-size:13px"><input type="checkbox" name="is_active" value="1" checked> نشط</label>
            </div>
            <div style="margin-top:10px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">إضافة</button>
                <button type="button" onclick="toggleForm()" class="btn">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:1.5rem">
    @php $colors = [['bg'=>'#E1F5EE','tc'=>'#0F6E56'],['bg'=>'#FAEEDA','tc'=>'#854F0B'],['bg'=>'#EEEDFE','tc'=>'#3C3489'],['bg'=>'#E6F1FB','tc'=>'#185FA5']]; @endphp
    @forelse($packages as $i => $p)
    @php $c = $colors[$i % 4]; @endphp
    <div class="card" style="text-align:center;border-top:3px solid {{ $c['tc'] }};position:relative">
        @if($p->badge)<div style="background:{{ $c['bg'] }};color:{{ $c['tc'] }};font-size:11px;padding:4px;border-radius:6px;margin-bottom:8px;display:inline-block">{{ $p->badge }}</div>@endif
        <div style="font-size:16px;font-weight:500;margin-bottom:4px">{{ $p->name }}</div>
        <div style="font-size:28px;font-weight:500;color:{{ $c['tc'] }};margin-bottom:4px">{{ number_format($p->price) }} <span style="font-size:14px">ريال</span></div>
        <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:4px">{{ $p->duration_days }} يوم</div>
        @if($p->description)<div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:8px">{{ $p->description }}</div>@endif
        @if($p->features)
        @foreach($p->features as $f)
        <div style="font-size:12px;color:var(--color-text-secondary);padding:3px 0;display:flex;align-items:center;gap:6px;justify-content:center"><i class="ti ti-check" style="color:#1D9E75"></i>{{ $f }}</div>
        @endforeach
        @endif
        <div style="margin-top:10px;display:flex;gap:6px;justify-content:center;border-top:1px solid var(--color-border);padding-top:10px">
            <a href="{{ route('admin.packages.edit', $p->id) }}" class="btn" style="padding:4px 10px;font-size:12px"><i class="ti ti-edit"></i> تعديل</a>
            <form method="POST" action="{{ route('admin.packages.destroy', $p->id) }}" onsubmit="return confirm('حذف {{ $p->name }}؟')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn" style="padding:4px 10px;font-size:12px;color:#A32D2D"><i class="ti ti-trash"></i> حذف</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--color-text-secondary)">لا توجد باقات. أضف أول باقة!</div>
    @endforelse
</div>

<script>function toggleForm(){var f=document.getElementById('createForm');f.style.display=f.style.display==='none'?'block':'none'}</script>
@endsection