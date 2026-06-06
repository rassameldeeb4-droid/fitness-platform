@extends('layouts.app')
@section('title', 'التايم لاين')
@section('content')
<div class="page-title">التايم لاين</div>
<div style="display:grid;grid-template-columns:1fr 300px;gap:1rem">
<div>
<form method="POST" action="{{ route('trainer.posts.store') }}" class="card" style="padding:10px 14px;display:flex;gap:8px;align-items:center;margin-bottom:1rem">
    @csrf
    <div class="avatar" style="width:36px;height:36px;background:#EEEDFE;color:#3C3489;font-size:13px">{{ substr(auth()->user()->name, 0, 1) }}</div>
    <input name="content" placeholder="شارك تحديثاً مع متدربيك..." style="flex:1;border:0.5px solid var(--color-border-secondary);border-radius:20px;padding:8px 14px;font-size:13px;background:var(--color-background-primary);color:var(--color-text-primary);font-family:var(--font-sans);outline:none" required>
    <button class="btn btn-primary" style="font-size:12px"><i class="ti ti-send"></i> نشر</button>
</form>

<div class="timeline" style="position:relative;padding-right:20px">
<div style="position:absolute;right:8px;top:0;bottom:0;width:1px;background:var(--color-border-tertiary)"></div>
@php
$typeMeta = [
    'nutrition_plan_assigned' => ['dot' => '#534AB7', 'tag' => 'غذائي', 'tagc' => 'badge-purple'],
    'workout_plan_assigned'  => ['dot' => '#854F0B', 'tag' => 'تمرين', 'tagc' => 'badge-amber'],
    'progress_logged'        => ['dot' => '#185FA5', 'tag' => 'قياسات', 'tagc' => 'badge-blue'],
    'member_added'           => ['dot' => '#1D9E75', 'tag' => 'جديد', 'tagc' => 'badge-green'],
];
@endphp
@forelse($latestPosts as $p)
<div style="position:relative;margin-bottom:1rem">
    <div style="position:absolute;right:-16px;top:4px;width:10px;height:10px;border-radius:50%;background:#1D9E75;border:2px solid var(--color-background-primary)"></div>
    <div style="font-size:11px;color:var(--color-text-tertiary);margin-bottom:3px">{{ $p->created_at->diffForHumans() }}</div>
    <div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:9px 12px">
        <div style="font-size:13px">{{ $p->content }}</div>
    </div>
</div>
@endforelse
@forelse($recentEvents as $e)
@php $m = $typeMeta[$e->type] ?? ['dot' => '#1D9E75', 'tag' => 'عام', 'tagc' => 'badge-green']; @endphp
<div style="position:relative;margin-bottom:1rem">
    <div style="position:absolute;right:-16px;top:4px;width:10px;height:10px;border-radius:50%;background:{{ $m['dot'] }};border:2px solid var(--color-background-primary)"></div>
    <div style="font-size:11px;color:var(--color-text-tertiary);margin-bottom:3px">{{ $e->created_at->diffForHumans() }}</div>
    <div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:9px 12px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:6px">
            <div>
                <div style="font-size:13px;font-weight:500">{{ $e->title }}</div>
                <div style="font-size:12px;color:var(--color-text-secondary);margin-top:2px">{{ $e->description }}</div>
            </div>
            <span class="badge {{ $m['tagc'] }}" style="flex-shrink:0">{{ $m['tag'] }}</span>
        </div>
    </div>
</div>
@empty
<div style="text-align:center;padding:2rem;color:var(--color-text-tertiary);font-size:13px">لا توجد أحداث بعد</div>
@endforelse
</div>
</div>

<div>
<div class="card">
    <div class="card-title"><i class="ti ti-chart-bar" style="color:#1D9E75"></i> إحصائياتي</div>
    <div style="display:flex;flex-direction:column;gap:8px">
        @php $stats = [['متدربون نشطون',$memberCount,'badge-green'],['خطط غذائية',$nutritionPlansCount,'badge-purple'],['خطط تدريبية',$workoutPlansCount,'badge-blue']]; @endphp
        @foreach($stats as $s)
        <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:4px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
            <span style="color:var(--color-text-secondary)">{{ $s[0] }}</span>
            <span class="badge {{ $s[2] }}">{{ $s[1] }}</span>
        </div>
        @endforeach
    </div>
</div>
<div class="card">
    <div class="card-title"><i class="ti ti-calendar" style="color:#534AB7"></i> جلسات اليوم</div>
    @forelse($todaySessions as $s)
    @php $statusMap = ['scheduled' => ['قادمة','badge-blue'], 'confirmed' => ['مؤكدة','badge-green'], 'completed' => ['منتهية','badge-amber'], 'cancelled' => ['ملغية','badge-red']]; $sm = $statusMap[$s->status] ?? ['قادمة','badge-blue']; @endphp
    <div style="display:flex;gap:8px;align-items:center;padding:6px 0;border-bottom:0.5px solid var(--color-border-tertiary);font-size:12px">
        <span style="color:#1D9E75;font-weight:500;min-width:35px">{{ $s->scheduled_at->format('H:i') }}</span>
        <span style="flex:1">{{ $s->member->name ?? '—' }}</span>
        <span class="badge {{ $sm[1] }}">{{ $sm[0] }}</span>
    </div>
    @empty
    <div style="text-align:center;padding:1rem;color:var(--color-text-tertiary);font-size:13px">لا توجد جلسات اليوم</div>
    @endforelse
</div>
</div>
</div>
@endsection
