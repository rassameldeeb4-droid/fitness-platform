@extends('layouts.app')
@section('title', $trainer->name)
@section('content')
<div class="page-title">{{ $trainer->name }}</div>
<div class="card">
    <div style="display:flex;gap:12px;align-items:center;">
        <div class="avatar" style="width:56px;height:56px;background:#EEEDFE;color:#3C3489;font-size:20px">{{ substr($trainer->name, 0, 1) }}</div>
        <div>
            <div style="font-size:17px;font-weight:500">{{ $trainer->name }}</div>
            <div style="font-size:12px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->specialty ?? '—' }}</div>
            <div style="margin-top:4px">
                <span class="badge badge-green">{{ $trainer->trainerProfile->certification ?? '—' }}</span>
                <span class="badge badge-blue">{{ $trainer->trainerProfile->experience_years ?? 0 }} سنوات</span>
            </div>
        </div>
        <div style="margin-right:auto;text-align:center">
            <div style="font-size:28px;font-weight:500;color:#854F0B">{{ $trainer->trainerProfile->rating ?? 0 }}</div>
            <div style="color:#854F0B;font-size:16px">{{ str_repeat('★', round($trainer->trainerProfile->rating ?? 0)) }}</div>
            <div style="font-size:11px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->review_count ?? 0 }} تقييم</div>
        </div>
    </div>
    @if($trainer->trainerProfile->bio)
        <div style="margin-top:12px;padding-top:12px;border-top:0.5px solid var(--color-border-tertiary);font-size:13px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->bio }}</div>
    @endif
</div>
@if($trainer->trainerReviews->count())
<div class="card">
    <div class="card-title"><i class="ti ti-star" style="color:#854F0B"></i> التقييمات</div>
    @foreach($trainer->trainerReviews as $review)
    <div style="padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <div style="font-size:12px">
            <span style="color:#854F0B">{{ str_repeat('★', $review->rating) }}</span>
            <span style="color:var(--color-text-tertiary)">{{ $review->member->name ?? '—' }}</span>
        </div>
        @if($review->review)<div style="font-size:12px;color:var(--color-text-secondary);margin-top:4px">{{ $review->review }}</div>@endif
    </div>
    @endforeach
</div>
@endif
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
    <div class="card">
        <div class="card-title"><i class="ti ti-user-check" style="color:#1D9E75"></i> متاح للتدريب</div>
        <div style="font-size:14px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->available ? 'نعم' : 'لا' }}</div>
    </div>
    <div class="card">
        <div class="card-title"><i class="ti ti-phone" style="color:#185FA5"></i> التواصل</div>
        <div style="font-size:14px;color:var(--color-text-secondary)">{{ $trainer->email }} • {{ $trainer->phone ?? '—' }}</div>
    </div>
</div>

@if($posts->count())
<div class="card" style="margin-top:1rem">
    <div class="card-title"><i class="ti ti-article" style="color:#185FA5"></i> المنشورات</div>
    @foreach($posts as $post)
    <div style="padding:12px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
        <div style="font-size:13px;line-height:1.6">{{ $post->content }}</div>
        @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" style="max-width:100%;border-radius:8px;margin-top:8px;max-height:300px">@endif
        <div style="font-size:11px;color:var(--color-text-secondary);margin-top:6px">{{ $post->created_at->diffForHumans() }} • {{ $post->comments->count() }} تعليق</div>
        @if(auth()->check())
        <form method="POST" action="{{ route('posts.comment', $post->id) }}" style="display:flex;gap:6px;margin-top:8px">
            @csrf
            <input type="text" name="content" placeholder="أضف تعليقاً..." style="flex:1;padding:6px 10px;border:0.5px solid var(--color-border);border-radius:6px;font-size:12px" required>
            <button type="submit" class="btn" style="padding:4px 12px;font-size:12px"><i class="ti ti-send"></i></button>
        </form>
        @foreach($post->comments as $comment)
        <div style="display:flex;gap:6px;margin-top:6px;font-size:12px">
            <span style="font-weight:500">{{ $comment->user->name ?? '?' }}</span>
            <span style="color:var(--color-text-secondary)">{{ $comment->content }}</span>
        </div>
        @endforeach
        @endif
    </div>
    @endforeach
</div>
@endif

@if($reels->count())
<div class="card" style="margin-top:1rem">
    <div class="card-title"><i class="ti ti-video" style="color:#854F0B"></i> الريلات</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px">
        @foreach($reels as $reel)
        <div style="border-radius:8px;overflow:hidden">
            <video style="width:100%;aspect-ratio:9/16;object-fit:cover;display:block" controls>
                <source src="{{ asset('storage/'.$reel->video) }}" type="video/mp4">
            </video>
            <div style="padding:6px 0;font-size:12px;font-weight:500">{{ $reel->title }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
