@extends('layouts.app')
@section('title', 'إضافة موعد')
@section('content')
<div class="page-title">إضافة موعد جديد</div>
<div class="card" style="max-width:500px">
    <form method="POST" action="{{ route('doctor.appointments.store') }}">
        @csrf
        <div style="display:grid;gap:12px">
            <div class="input-grp">
                <label>المريض</label>
                <select name="member_id" required>
                    <option value="">اختر مريض</option>
                    @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('member_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('member_id') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>تاريخ ووقت الموعد</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                @error('scheduled_at') <div style="color:var(--color-text-danger);font-size:12px">{{ $message }}</div> @enderror
            </div>
            <div class="input-grp">
                <label>المدة (دقيقة)</label>
                <select name="duration_minutes" required>
                    @foreach([15,30,45,60,90,120] as $min)
                    <option value="{{ $min }}" {{ old('duration_minutes', 30) == $min ? 'selected' : '' }}>{{ $min }} دقيقة</option>
                    @endforeach
                </select>
            </div>
            <div class="input-grp">
                <label>ملاحظات</label>
                <textarea name="notes" rows="3" placeholder="ملاحظات حول الموعد...">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;display:flex;gap:8px">
            <button type="submit" class="btn btn-primary">حجز الموعد</button>
            <a href="{{ route('doctor.appointments') }}" class="btn">إلغاء</a>
        </div>
    </form>
</div>
@endsection
