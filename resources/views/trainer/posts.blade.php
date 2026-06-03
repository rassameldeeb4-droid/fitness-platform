@extends('layouts.app')
@section('title', 'المنشورات')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">المنشورات</div>
    <button onclick="toggleForm()" class="btn btn-primary"><i class="ti ti-plus"></i> منشور جديد</button>
</div>

@if(session('success'))<div style="background:#E1F5EE;color:#0F6E56;padding:10px 16px;border-radius:8px;margin-bottom:1rem;font-size:14px">{{ session('success') }}</div>@endif

<div id="createForm" style="display:none;margin-bottom:1.5rem;max-width:600px">
    <div class="card">
        <div style="font-weight:500;margin-bottom:12px;font-size:15px">إضافة منشور جديد</div>
        <form method="POST" action="{{ route('trainer.posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-grp"><label>المحتوى</label><textarea name="content" rows="4" required></textarea></div>
            <div class="input-grp"><label>صورة (اختياري)</label><input type="file" name="image" accept="image/*"></div>
            <div style="margin-top:10px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">نشر</button>
                <button type="button" onclick="toggleForm()" class="btn">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<div style="max-width:600px;margin:0 auto">
    @forelse($posts as $post)
    <div class="card" style="margin-bottom:1rem">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
            <div class="avatar" style="width:40px;height:40px;background:#E1F5EE;color:#0F6E56;font-size:16px">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div>
                <div style="font-weight:500;font-size:14px">{{ auth()->user()->name }}</div>
                <div style="font-size:11px;color:var(--color-text-secondary)">{{ $post->created_at->diffForHumans() }}</div>
            </div>
            <div style="margin-right:auto">
                <form method="POST" action="{{ route('trainer.posts.destroy', $post->id) }}" onsubmit="return confirm('حذف المنشور؟')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="padding:2px 8px;font-size:11px;color:#A32D2D"><i class="ti ti-trash"></i></button>
                </form>
            </div>
        </div>
        <div style="font-size:14px;line-height:1.6;margin-bottom:10px">{{ $post->content }}</div>
        @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" style="max-width:100%;border-radius:8px;margin-bottom:10px">@endif

        <div style="border-top:1px solid var(--color-border);padding-top:10px;margin-top:10px">
            <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:8px">{{ $post->comments->count() }} تعليق</div>
            @foreach($post->comments as $comment)
            <div style="display:flex;gap:8px;margin-bottom:6px;font-size:13px">
                <div class="avatar" style="width:28px;height:28px;background:#EEEDFE;color:#3C3489;font-size:11px;flex-shrink:0">{{ substr($comment->user->name ?? '?', 0, 1) }}</div>
                <div style="flex:1">
                    <span style="font-weight:500">{{ $comment->user->name ?? '?' }}</span>
                    <span style="color:var(--color-text-secondary)">{{ $comment->content }}</span>
                    <div style="font-size:10px;color:var(--color-text-secondary)">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:3rem;color:var(--color-text-secondary)">لا توجد منشورات بعد. أنشئ أول منشور!</div>
    @endforelse
    <div style="margin-top:1rem">{{ $posts->links() }}</div>
</div>

<script>function toggleForm(){var f=document.getElementById('createForm');f.style.display=f.style.display==='none'?'block':'none'}</script>
@endsection