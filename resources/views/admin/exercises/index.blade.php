@extends('layouts.app')
@section('title', 'التمارين')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
    <div class="page-title" style="margin-bottom:0">التمارين</div>
    <a href="{{ route('admin.exercises.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> إضافة تمرين</a>
</div>
<div class="card">
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الفئة</th>
                <th>العضلة</th>
                <th>المجموعات</th>
                <th>العدات</th>
                <th>الصعوبة</th>
                <th>فيديو</th>
                <th>الحالة</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($exercises as $e)
            <tr>
                <td style="color:var(--color-text-tertiary)">{{ $e->id }}</td>
                <td>{{ $e->name }}</td>
                <td><span class="badge badge-purple">{{ $e->category }}</span></td>
                <td>{{ $e->muscle_group }}</td>
                <td>{{ $e->sets_default }}</td>
                <td>{{ $e->reps_default }}</td>
                <td><span class="badge {{ $e->difficulty === 'beginner' ? 'badge-green' : ($e->difficulty === 'intermediate' ? 'badge-amber' : 'badge-red') }}">{{ $e->difficulty === 'beginner' ? 'مبتدئ' : ($e->difficulty === 'intermediate' ? 'متوسط' : 'متقدم') }}</span></td>
                <td>
                    @if($e->video_url)
                        @if(filter_var($e->video_url, FILTER_VALIDATE_URL))
                        <a href="{{ $e->video_url }}" target="_blank" class="badge badge-blue"><i class="ti ti-video"></i> رابط</a>
                        @else
                        <a href="{{ Storage::url($e->video_url) }}" target="_blank" class="badge badge-blue"><i class="ti ti-video"></i> فيديو</a>
                        @endif
                    @else — @endif
                </td>
                <td><span class="badge {{ $e->is_active ? 'badge-green' : 'badge-red' }}">{{ $e->is_active ? 'نشط' : 'غير نشط' }}</span></td>
                <td>
                    <a href="{{ route('admin.exercises.edit', $e) }}" class="btn btn-sm"><i class="ti ti-edit"></i></a>
                    <form method="POST" action="{{ route('admin.exercises.destroy', $e) }}" style="display:inline" onsubmit="return confirm('حذف التمرين؟')">@csrf @method('DELETE')<button class="btn btn-sm" style="color:#A32D2D"><i class="ti ti-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;color:var(--color-text-tertiary);padding:2rem">لا توجد تمارين</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
