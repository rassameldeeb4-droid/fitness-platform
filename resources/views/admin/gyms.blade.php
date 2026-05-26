@extends('layouts.app')
@section('title', 'الصالات')
@section('content')
<div class="page-title">إدارة الصالات</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
    @forelse($gyms as $gym)
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px">
            <div style="font-size:15px;font-weight:500">{{ $gym->name }}</div>
            <span class="badge badge-green">{{ $gym->status === 'active' ? 'نشطة' : 'غير نشطة' }}</span>
        </div>
        <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:6px"><i class="ti ti-map-pin"></i> {{ $gym->city }} — {{ $gym->address }}</div>
        <div style="display:flex;gap:1rem;font-size:12px;color:var(--color-text-tertiary)">
            <span><i class="ti ti-users"></i> {{ $gym->users_count ?? 0 }} عضو</span>
            <span><i class="ti ti-user-star"></i> {{ $gym->trainer_count }} مدرب</span>
        </div>
    </div>
    @empty
    <div class="card" style="grid-column:1/-1;text-align:center;color:var(--color-text-secondary)">لا توجد صالات مسجلة</div>
    @endforelse
</div>
<div class="card" style="margin-top:1rem">
    <div class="card-title">إضافة صالة جديدة</div>
    <form method="POST" action="{{ route('admin.gyms.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>اسم الصالة</label><input name="name" required></div>
            <div class="input-grp"><label>المدينة</label><input name="city" required></div>
            <div class="input-grp"><label>العنوان</label><input name="address" required></div>
            <div class="input-grp"><label>رقم الهاتف</label><input name="phone"></div>
        </div>
        <button class="btn btn-primary"><i class="ti ti-plus"></i> إضافة</button>
    </form>
</div>
@endsection
