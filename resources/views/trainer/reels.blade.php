@extends('layouts.app')
@section('title', 'الريلات')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">الريلات</div>
    <button onclick="toggleForm()" class="btn btn-primary"><i class="ti ti-plus"></i> ريل جديد</button>
</div>

@if(session('success'))<div style="background:#E1F5EE;color:#0F6E56;padding:10px 16px;border-radius:8px;margin-bottom:1rem;font-size:14px">{{ session('success') }}</div>@endif

<div id="createForm" style="display:none;margin-bottom:1.5rem;max-width:500px">
    <div class="card">
        <div style="font-weight:500;margin-bottom:12px;font-size:15px">رفع ريل جديد</div>
        <form method="POST" action="{{ route('trainer.reels.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-grp"><label>العنوان</label><input type="text" name="title" required></div>
            <div class="input-grp"><label>الوصف</label><textarea name="description" rows="2"></textarea></div>
            <div class="input-grp"><label>الفيديو (MP4, max 50MB)</label><input type="file" name="video" accept="video/*" required></div>
            <div style="margin-top:10px;display:flex;gap:8px">
                <button type="submit" class="btn btn-primary">رفع</button>
                <button type="button" onclick="toggleForm()" class="btn">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px">
    @forelse($reels as $reel)
    <div class="card" style="padding:0;overflow:hidden">
        <video style="width:100%;aspect-ratio:9/16;object-fit:cover;display:block" controls>
            <source src="{{ asset('storage/'.$reel->video) }}" type="video/mp4">
        </video>
        <div style="padding:12px">
            <div style="font-weight:500;font-size:14px;margin-bottom:4px">{{ $reel->title }}</div>
            @if($reel->description)<div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:8px">{{ $reel->description }}</div>@endif
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:11px;color:var(--color-text-secondary)">
                <span>{{ $reel->created_at->diffForHumans() }}</span>
                <form method="POST" action="{{ route('trainer.reels.destroy', $reel->id) }}" onsubmit="return confirm('حذف الريل؟')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="padding:2px 8px;font-size:11px;color:#A32D2D"><i class="ti ti-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--color-text-secondary)">لا توجد ريلات بعد</div>
    @endforelse
</div>

<div style="margin-top:1rem">{{ $reels->links() }}</div>

<script>function toggleForm(){var f=document.getElementById('createForm');f.style.display=f.style.display==='none'?'block':'none'}</script>
@endsection