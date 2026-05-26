@extends('layouts.app')
@section('title', 'مكتبة التمارين')
@push('styles')
<style>
.ex-card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);overflow:hidden;margin-bottom:8px}
.ex-card-thumb{height:100px;display:flex;align-items:center;justify-content:center;position:relative;background:linear-gradient(135deg,#534AB7 0%,#1D9E75 100%)}
.ex-play{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.9);display:flex;align-items:center;justify-content:center;cursor:pointer}
.ex-card-body{padding:10px 12px}
.ex-card-name{font-size:13px;font-weight:500}
.ex-card-meta{font-size:11px;color:var(--color-text-secondary);margin-top:3px;display:flex;gap:8px}
</style>
@endpush
@section('content')
<div class="page-title">مكتبة التمارين</div>
<div style="display:flex;gap:8px;margin-bottom:1rem;align-items:center">
    <input placeholder="ابحث عن تمرين..." style="flex:1;padding:8px 12px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;font-family:var(--font-sans);outline:none">
    <button class="btn btn-primary"><i class="ti ti-plus"></i> إضافة تمرين</button>
</div>
<div class="food-cat-filter" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:1rem">
    @foreach($categories as $i => $cat)
    <span class="cat-pill {{ $i === 0 ? 'active' : '' }}" onclick="filterEx('{{ $cat }}', this)">{{ $cat }}</span>
    @endforeach
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:10px">
    @forelse($exercises as $ex)
    <div class="ex-card ex-card-all" data-cat="{{ $ex->category }}">
        <div class="ex-card-thumb" style="background:linear-gradient(135deg,rgba(83,74,183,0.6),rgba(83,74,183,0.2))">
            <div style="position:absolute;top:8px;right:8px"><span class="badge badge-purple" style="font-size:10px">{{ $ex->category }}</span></div>
            <div class="ex-play"><i class="ti ti-player-play" style="color:#534AB7;font-size:18px"></i></div>
        </div>
        <div class="ex-card-body">
            <div class="ex-card-name">{{ $ex->name }}</div>
            <div class="ex-card-meta">
                <span><i class="ti ti-repeat"></i> {{ $ex->sets_default }} مج</span>
                <span><i class="ti ti-hash"></i> {{ $ex->reps_default }} تكرار</span>
                <span><i class="ti ti-muscle"></i> {{ $ex->muscle_group }}</span>
            </div>
            <div style="display:flex;gap:6px;margin-top:8px">
    <a href="{{ route('exercises.show', $ex->id) }}" class="btn btn-primary" style="flex:1;font-size:11px;padding:5px 8px;text-align:center"><i class="ti ti-eye"></i> عرض</a>
    <a href="{{ route('chat.index') }}" class="btn" style="flex:1;font-size:11px;padding:5px 8px;text-align:center"><i class="ti ti-send"></i> أرسل لمتدرب</a>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--color-text-secondary)">لا توجد تمارين بعد</div>
    @endforelse
</div>
<script>
function filterEx(cat, el) {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.ex-card-all').forEach(c => {
        c.style.display = (cat === 'الكل' || c.dataset.cat === cat) ? 'block' : 'none';
    });
}
</script>
@endsection
