@extends('layouts.app')
@section('title', 'إنشاء خطة تدريب')
@section('content')
<div class="page-title">إنشاء خطة تدريب لـ {{ $member->name }}</div>
<div class="card">
    <div class="card-title"><i class="ti ti-barbell" style="color:#534AB7"></i> بيانات خطة التدريب</div>
    <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="input-grp"><label>الهدف</label><select id="w-goal"><option>خسارة وزن</option><option>زيادة كتلة عضلية</option><option>تنشيف</option><option>لياقة عامة</option></select></div>
        <div class="input-grp"><label>مستوى الخبرة</label><select id="w-level"><option>مبتدئ</option><option selected>متوسط</option><option>متقدم</option></select></div>
    </div>
    <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="input-grp"><label>أيام التمرين/أسبوع</label><input id="w-days" type="number" value="4"></div>
    </div>
    <button type="button" class="btn btn-ai" onclick="generateWorkout()"><i class="ti ti-barbell"></i> إنشاء خطة التدريب تلقائياً ↗</button>
</div>
<div id="workout-output"></div>
@endsection
@push('scripts')
<script>
async function generateWorkout(){
    const data = {
        name: '{{ $member->name }}',
        goal: document.getElementById('w-goal').value,
        level: document.getElementById('w-level').value,
        days: document.getElementById('w-days').value,
        _token: '{{ csrf_token() }}'
    };
    const out = document.getElementById('workout-output');
    out.innerHTML = `<div style="text-align:center;padding:2rem;color:var(--color-text-secondary)"><div class="spinner"></div> الذكاء الاصطناعي يصمم خطة التدريب...</div>`;
    try {
        const resp = await fetch('{{ route('ai.generate-workout') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify(data)
        });
        const plan = await resp.json();
        if(plan.error) { out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">${plan.error}</div>`; return; }
        out.innerHTML = `<div style="background:#EEEDFE;border:0.5px solid #AFA9EC;border-radius:var(--border-radius-lg);padding:1.25rem;margin-top:1rem">
        <div style="font-size:14px;font-weight:500;color:#3C3489;margin-bottom:0.75rem"><i class="ti ti-barbell"></i> خطة التدريب لـ ${data.name} — ${data.goal}</div>
        ${(plan.days||[]).map(d=>'<div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:10px 14px;margin-bottom:8px"><div style="font-size:13px;font-weight:500;margin-bottom:6px">'+d.day+' — <span style="color:#1D9E75">'+d.focus+'</span></div>'+(d.exercises||[]).map(e=>'<div style="font-size:12px;color:var(--color-text-secondary);padding:2px 0;display:flex;justify-content:space-between"><span>'+e.name+'</span><span>'+e.sets+' × '+e.reps+' | راحة '+e.rest+'</span></div>').join('')+'</div>').join('')}
        ${plan.tips ? '<div style="margin-top:8px;font-size:12px;color:#534AB7;background:var(--color-background-primary);padding:8px 12px;border-radius:var(--border-radius-md)">'+plan.tips+'</div>' : ''}
        </div>`;
    } catch(e) {
        out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">حدث خطأ: ${e.message}</div>`;
    }
}
</script>
@endpush
