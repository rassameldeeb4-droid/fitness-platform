@extends('layouts.app')
@section('title', 'المشتركون')
@section('content')
<div class="page-title">إدارة المشتركين</div>
<div style="display:flex;gap:8px;margin-bottom:1rem">
    <input type="text" placeholder="بحث عن مشترك..." style="flex:1;padding:8px 12px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;font-family:var(--font-sans);color:var(--color-text-primary)">
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة مشترك</a>
</div>
<div class="card" style="padding:0">
    <table>
        <thead><tr><th>المشترك</th><th>الباقة</th><th>المدرب</th><th>تاريخ الانتهاء</th><th>الحالة</th></tr></thead>
        <tbody>
            @forelse($members as $member)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar" style="width:36px;height:36px;background:#E1F5EE;color:#0F6E56;font-size:12px">{{ substr($member->name, 0, 1) }}</div>
                        <a href="{{ route('admin.members.show', $member->id) }}" style="font-weight:500">{{ $member->name }}</a>
                    </div>
                </td>
                <td>{{ $member->subscriptions->first()->package->name ?? '—' }}</td>
                <td>{{ $member->memberProfile->trainer->name ?? '—' }}</td>
                <td style="color:var(--color-text-secondary)">{{ $member->subscriptions->first()->end_date ?? '—' }}</td>
                <td><span class="badge {{ ($member->subscriptions->first()->status ?? '') === 'active' ? 'badge-green' : 'badge-red' }}">{{ ($member->subscriptions->first()->status ?? '') === 'active' ? 'نشط' : 'منتهية' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:var(--color-text-secondary)">لا يوجد مشتركون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
