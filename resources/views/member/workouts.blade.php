@extends('layouts.app')
@section('title', 'جدول التمارين')
@section('content')
<div class="page-title">جدول تماريني الأسبوعي</div>
@if($plan)
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
    @foreach($plan->days as $day)
    <div class="card" style="padding:0;overflow:hidden">
        <div style="background:#EEEDFE;padding:10px 14px;display:flex;justify-content:space-between;align-items:center">
            <div>
                <div style="font-size:14px;font-weight:500">{{ $day->day_name }}</div>
                <div style="font-size:12px;color:#534AB7">{{ $day->focus }}</div>
            </div>
            <span class="badge {{ $day->is_completed ? 'badge-green' : 'badge-gray' }}">{{ $day->is_completed ? 'مكتمل' : 'قادم' }}</span>
        </div>
        <div style="padding:10px 14px">
            @foreach($day->exercises as $ex)
            <div style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
                <div style="width:32px;height:32px;border-radius:var(--border-radius-md);background:#EEEDFE;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="ti ti-barbell" style="color:#534AB7;font-size:14px"></i>
                </div>
                <div style="flex:1">
                    <div style="font-size:12px;font-weight:500">{{ $ex->name }}</div>
                    <div style="font-size:11px;color:var(--color-text-secondary)">{{ $ex->pivot->sets ?? 3 }} مج • {{ $ex->pivot->reps ?? 12 }} تكرار • راحة {{ $ex->pivot->rest_seconds ?? '60' }}ث</div>
                </div>
                @if($ex->video_url)
                <span style="background:#E6F1FB;color:#185FA5;border-radius:4px;padding:2px 7px;font-size:10px;cursor:pointer" onclick="openVid('{{ $ex->video_url }}','{{ $ex->name }}','{{ $ex->description ?? '' }}')"><i class="ti ti-video"></i> فيديو</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card" style="text-align:center;padding:2rem">
    <i class="ti ti-barbell" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:1rem;display:block"></i>
    <p style="color:var(--color-text-secondary);font-size:14px">لم يتم إنشاء خطة تدريب لك بعد</p>
    <p style="color:var(--color-text-tertiary);font-size:12px;margin-top:4px">تواصل مع مدربك ليُصمم لك برنامجاً مخصصاً</p>
</div>
@endif

<!-- Video Modal -->
<div id="vid-modal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);z-index:100;display:none;align-items:center;justify-content:center" onclick="closeVid(event)">
<div style="background:var(--color-background-primary);border-radius:var(--border-radius-lg);padding:1.25rem;width:480px;max-width:95vw" onclick="event.stopPropagation()">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <span id="vid-title" style="font-size:15px;font-weight:500">فيديو التمرين</span>
        <button class="btn" onclick="closeVid()" style="padding:4px 10px"><i class="ti ti-x"></i></button>
    </div>
    <div id="vid-body" style="background:#000;border-radius:var(--border-radius-md);aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;overflow:hidden"></div>
    <div id="vid-desc" style="margin-top:10px;font-size:13px;color:var(--color-text-secondary)"></div>
</div>
</div>
@endsection
@push('scripts')
<script>
function openVid(url, name, desc) {
    document.getElementById('vid-title').textContent = name;
    document.getElementById('vid-body').innerHTML = '<iframe width="100%" height="100%" src="'+url+'" frameborder="0" allow="autoplay;encrypted-media" allowfullscreen style="border-radius:6px"></iframe>';
    document.getElementById('vid-desc').textContent = desc;
    document.getElementById('vid-modal').style.display = 'flex';
}
function closeVid(e) {
    if(!e || e.target === document.getElementById('vid-modal')) {
        document.getElementById('vid-body').innerHTML = '';
        document.getElementById('vid-modal').style.display = 'none';
    }
}
</script>
@endpush
