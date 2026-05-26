@extends('layouts.app')
@section('title', 'تقييم الصالات')
@section('content')
<div class="page-title">تقييم الصالات</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
    @forelse($gyms as $gym)
    <div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden;margin-bottom:10px">
        <div style="height:80px;background:linear-gradient(135deg,#185FA5,#1D9E75);display:flex;align-items:center;justify-content:center">
            <i class="ti ti-building" style="font-size:36px;color:rgba(255,255,255,0.6)"></i>
        </div>
        <div style="padding:10px 12px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px">
                <div><div style="font-size:15px;font-weight:500">{{ $gym->name }}</div>
                <div style="font-size:12px;color:var(--color-text-secondary)"><i class="ti ti-map-pin"></i> {{ $gym->city }}</div></div>
                <div style="text-align:center"><div style="font-size:18px;font-weight:500;color:#854F0B">4.8</div><div style="font-size:12px;color:#854F0B">★★★★★</div></div>
            </div>
            <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:10px">
                <span class="badge badge-green" style="font-size:10px">معدات حديثة</span>
                <span class="badge badge-green" style="font-size:10px">نظيفة</span>
            </div>
            <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:8px">{{ $gym->users_count ?? 0 }} عضو • {{ $gym->trainer_count }} مدرب</div>
            <form method="POST" action="{{ route('gyms.rate') }}" style="display:flex;gap:4px">
                @csrf
                <input type="hidden" name="gym_id" value="{{ $gym->id }}">
                <div class="input-grp" style="flex:1">
                    <select name="rating" style="font-size:12px;padding:5px 8px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);font-family:var(--font-sans)">
                        <option value="">أضف تقييمك</option>
                        <option value="5">★★★★★ ممتاز</option>
                        <option value="4">★★★★ جيد جداً</option>
                        <option value="3">★★★ جيد</option>
                        <option value="2">★★ مقبول</option>
                        <option value="1">★ سيء</option>
                    </select>
                </div>
                <button class="btn btn-primary" style="font-size:12px;padding:5px 10px"><i class="ti ti-check"></i></button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--color-text-secondary)">لا توجد صالات بعد</div>
    @endforelse
</div>
@endsection
