@extends('layouts.app')
@section('title', $exercise->name)
@section('content')
<a href="{{ route('exercises') }}" class="btn btn-sm" style="margin-bottom:1rem"><i class="ti ti-arrow-right"></i> العودة</a>
<div class="page-title">{{ $exercise->name }}</div>
<div style="display:grid;grid-template-columns:300px 1fr;gap:1rem">
    <div>
        <div class="card" style="overflow:hidden;padding:0">
            @if($exercise->video_url)
                @if(filter_var($exercise->video_url, FILTER_VALIDATE_URL) && str_contains($exercise->video_url, 'youtube'))
                <div style="position:relative;padding-bottom:56.25%;height:0">
                    <iframe src="{{ str_replace('watch?v=', 'embed/', $exercise->video_url) }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none" allowfullscreen></iframe>
                </div>
                @elseif(!filter_var($exercise->video_url, FILTER_VALIDATE_URL))
                <video controls style="width:100%;max-height:200px;background:#000">
                    <source src="{{ Storage::url($exercise->video_url) }}" type="video/mp4">
                </video>
                @else
                <div style="height:150px;display:flex;align-items:center;justify-content:center;background:var(--color-background-secondary);color:var(--color-text-tertiary);font-size:13px">
                    <a href="{{ $exercise->video_url }}" target="_blank"><i class="ti ti-video"></i> مشاهدة الفيديو</a>
                </div>
                @endif
            @elseif($exercise->image)
            <img src="{{ Storage::url($exercise->image) }}" alt="" style="width:100%;max-height:200px;object-fit:cover">
            @else
            <div style="height:150px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#534AB7,#1D9E75);color:#fff;font-size:48px"><i class="ti ti-barbell"></i></div>
            @endif
        </div>
        <div class="card">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px">
                <div><span style="color:var(--color-text-secondary)">الفئة:</span> <span class="badge badge-purple">{{ $exercise->category }}</span></div>
                <div><span style="color:var(--color-text-secondary)">العضلة:</span> {{ $exercise->muscle_group }}</div>
                <div><span style="color:var(--color-text-secondary)">المجموعات:</span> {{ $exercise->sets_default }}</div>
                <div><span style="color:var(--color-text-secondary)">العدات:</span> {{ $exercise->reps_default }}</div>
                <div><span style="color:var(--color-text-secondary)">الصعوبة:</span> <span class="badge {{ $exercise->difficulty === 'beginner' ? 'badge-green' : ($exercise->difficulty === 'intermediate' ? 'badge-amber' : 'badge-red') }}">{{ $exercise->difficulty === 'beginner' ? 'مبتدئ' : ($exercise->difficulty === 'intermediate' ? 'متوسط' : 'متقدم') }}</span></div>
                <div><span style="color:var(--color-text-secondary)">الأجهزة:</span> {{ $exercise->equipment ?? '—' }}</div>
                @if($exercise->calories_per_set)
                <div><span style="color:var(--color-text-secondary)">سعرات/مجموعة:</span> {{ $exercise->calories_per_set }}</div>
                @endif
            </div>
        </div>
    </div>
    <div>
        <div class="card" style="min-height:200px">
            <div class="card-title">شرح التمرين</div>
            <div style="font-size:14px;color:var(--color-text-secondary);line-height:1.8">
                {{ $exercise->description ?? 'لا يوجد شرح متوفر لهذا التمرين' }}
            </div>
        </div>
    </div>
</div>
<style>
@media(max-width:768px){
    div[style*="grid-template-columns: 300px 1fr"]{grid-template-columns:1fr!important}
}
</style>
@endsection
