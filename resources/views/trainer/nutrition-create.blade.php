@extends('layouts.app')
@section('title', 'إنشاء نظام غذائي')
@section('content')
<div class="page-title">إنشاء نظام غذائي لـ {{ $member->name }}</div>
<div class="card">
    <div class="card-title"><i class="ti ti-brain" style="color:#534AB7"></i> بيانات المتدرب</div>
    <form id="nutritionForm">
        @csrf
        <input type="hidden" name="member_id" value="{{ $member->id }}">
        <input type="hidden" id="n-name" value="{{ $member->name }}">
        <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>العمر</label><input id="n-age" type="number" value="{{ $member->memberProfile->age ?? 28 }}"></div>
            <div class="input-grp"><label>الوزن (كغ)</label><input id="n-weight" type="number" value="{{ $member->memberProfile->current_weight ?? 92 }}"></div>
        </div>
        <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>الطول (سم)</label><input id="n-height" type="number" value="{{ $member->memberProfile->height ?? 178 }}"></div>
            <div class="input-grp"><label>الهدف</label><select id="n-goal"><option>خسارة وزن</option><option>زيادة كتلة عضلية</option><option>تنشيف</option><option>لياقة عامة</option></select></div>
        </div>
        <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>مستوى النشاط</label><select id="n-activity"><option>خفيف</option><option selected>متوسط</option><option>مكثف</option></select></div>
            <div class="input-grp"><label>نسبة الدهون (%)</label><input id="n-fat" type="number" value="{{ $member->memberProfile->body_fat ?? 24 }}"></div>
        </div>
        <div class="input-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
            <div class="input-grp"><label>أيام التمرين/أسبوع</label><input id="n-days" type="number" value="4"></div>
        </div>
        <button type="button" class="btn btn-ai" onclick="generateNutrition()"><i class="ti ti-brain"></i> إنشاء النظام الغذائي تلقائياً ↗</button>
    </form>
</div>
<div id="nutrition-output"></div>
@endsection
@push('scripts')
<script>
async function generateNutrition(){
    const data = {
        name: document.getElementById('n-name').value,
        age: document.getElementById('n-age').value,
        weight: document.getElementById('n-weight').value,
        height: document.getElementById('n-height').value,
        goal: document.getElementById('n-goal').value,
        activity_level: document.getElementById('n-activity').value,
        body_fat: document.getElementById('n-fat').value,
        workout_days: document.getElementById('n-days').value,
        _token: '{{ csrf_token() }}'
    };
    const out = document.getElementById('nutrition-output');
    out.innerHTML = `<div style="text-align:center;padding:2rem;color:var(--color-text-secondary)"><div class="spinner" style="border-color:#534AB7;border-top-color:transparent;margin-left:8px;display:inline-block;width:18px;height:18px;border:2px solid rgba(83,74,183,0.3);border-top-color:#534AB7;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle"></div> الذكاء الاصطناعي يحسب النظام الغذائي المثالي...</div>`;
    try {
        const resp = await fetch('{{ route('ai.generate-nutrition') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify(data)
        });
        const plan = await resp.json();
        if(plan.error) { out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">${plan.error}</div>`; return; }
        out.innerHTML = `<div style="background:#EEEDFE;border:0.5px solid #AFA9EC;border-radius:var(--border-radius-lg);padding:1.25rem;margin-top:1rem">
        <div style="font-size:14px;font-weight:500;color:#3C3489;margin-bottom:0.75rem"><i class="ti ti-sparkles" style="color:#534AB7"></i> النظام الغذائي المقترح لـ ${data.name}</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:1rem">
            <div style="background:var(--color-background-primary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">السعرات</div><div style="font-size:20px;font-weight:500;color:#534AB7">${plan.calories}</div></div>
            <div style="background:var(--color-background-primary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">بروتين</div><div style="font-size:20px;font-weight:500;color:#1D9E75">${plan.protein}غ</div></div>
            <div style="background:var(--color-background-primary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">كارب</div><div style="font-size:20px;font-weight:500;color:#185FA5">${plan.carbs}غ</div></div>
            <div style="background:var(--color-background-primary);border-radius:var(--border-radius-md);padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">دهون</div><div style="font-size:20px;font-weight:500;color:#854F0B">${plan.fat}غ</div></div>
        </div>
        ${(plan.meals||[]).map(m=>'<div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:10px 12px;margin-bottom:8px"><div style="display:flex;justify-content:space-between"><div style="font-size:13px;font-weight:500">'+m.name+' <span style="color:var(--color-text-tertiary);font-size:11px">'+m.time+'</span></div><span class="badge badge-green">'+m.calories+' سعرة</span></div><div style="font-size:12px;color:var(--color-text-secondary);margin-top:4px">'+(m.items||[]).join(' • ')+'</div></div>').join('')}
        ${plan.notes ? '<div style="margin-top:8px;font-size:12px;color:#534AB7;background:var(--color-background-primary);padding:8px 12px;border-radius:var(--border-radius-md)">'+plan.notes+'</div>' : ''}
        </div>`;
    } catch(e) {
        out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">حدث خطأ: ${e.message}</div>`;
    }
}
</script>
@endpush
