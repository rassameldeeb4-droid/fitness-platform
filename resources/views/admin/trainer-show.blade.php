@extends('layouts.app')
@section('title', 'بروفايل المدرب')
@section('content')
<div class="page-title">{{ $trainer->name }}</div>
<div class="card">
    <div style="display:flex;gap:12px;align-items:center;">
        <div class="avatar" style="width:56px;height:56px;background:#EEEDFE;color:#3C3489;font-size:20px">{{ substr($trainer->name, 0, 1) }}</div>
        <div>
            <div style="font-size:17px;font-weight:500">{{ $trainer->name }}</div>
            <div style="font-size:12px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->specialty ?? '—' }}</div>
            <div style="margin-top:4px"><span class="badge badge-green">{{ $trainer->trainerProfile->certification ?? '—' }}</span><span class="badge badge-blue">{{ $trainer->trainerProfile->experience_years ?? 0 }} سنوات</span></div>
        </div>
        <div style="margin-right:auto;text-align:center">
            <div style="font-size:28px;font-weight:500;color:#854F0B">{{ $trainer->trainerProfile->rating ?? 0 }}</div>
            <div style="color:#854F0B;font-size:16px">{{ str_repeat('★', round($trainer->trainerProfile->rating ?? 0)) }}</div>
            <div style="font-size:11px;color:var(--color-text-secondary)">{{ $trainer->trainerProfile->review_count ?? 0 }} تقييم</div>
        </div>
    </div>
</div>
@if($trainer->trainerReviews->count())
<div class="card"><div class="card-title"><i class="ti ti-star" style="color:#854F0B"></i> التقييمات</div>
@foreach($trainer->trainerReviews as $review)
<div style="padding:8px 0;border-bottom:0.5px solid var(--color-border-tertiary)">
    <div style="font-size:12px"><span style="color:#854F0B">{{ str_repeat('★', $review->rating) }}</span> <span style="color:var(--color-text-tertiary)">{{ $review->member->name ?? '—' }}</span></div>
    @if($review->review)<div style="font-size:12px;color:var(--color-text-secondary);margin-top:4px">{{ $review->review }}</div>@endif
</div>
@endforeach</div>
@endif
@endsection
