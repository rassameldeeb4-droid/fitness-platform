@extends('layouts.app')
@section('title', 'نظام غذائي ذكي')
@section('content')
<div class="page-title"><i class="ti ti-sparkles" style="color:#534AB7"></i> النظام الغذائي الذكي</div>

<div class="card" id="metrics-card">
    <div class="card-title"><i class="ti ti-edit"></i> بيانات المتدرب</div>
    <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px">أدخل القياسات وسيقوم الذكاء الاصطناعي بإنشاء نظام غذائي مخصص</div>
    <form id="metricsForm">
        @csrf
        <input type="hidden" name="member_id" value="{{ $member->id }}">
        <input type="hidden" id="n-name" value="{{ $member->name }}">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:10px">
            <div class="input-grp"><label>الطول (سم)</label><input id="n-height" type="number" value="{{ old('height', $member->memberProfile->height ?? '') }}" placeholder="مثال: 175"></div>
            <div class="input-grp"><label>الوزن (كغ)</label><input id="n-weight" type="number" value="{{ old('weight', $member->memberProfile->current_weight ?? '') }}" placeholder="مثال: 80"></div>
            <div class="input-grp"><label>العمر</label><input id="n-age" type="number" value="{{ old('age', $member->memberProfile->age ?? '') }}" placeholder="مثال: 30"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:10px">
            <div class="input-grp"><label>محيط الرصغ (سم)</label><input id="n-wrist" type="number" step="0.1" value="{{ old('wrist', $member->memberProfile->wrist_circumference ?? '') }}" placeholder="مثال: 17"></div>
            <div class="input-grp"><label>محيط الوسط (سم)</label><input id="n-waist" type="number" step="0.1" value="{{ old('waist', $member->memberProfile->waist_circumference ?? '') }}" placeholder="مثال: 85"></div>
            <div class="input-grp"><label>نسبة الدهون (%)</label><input id="n-fat" type="number" step="0.1" value="{{ old('body_fat', $member->memberProfile->body_fat ?? '') }}" placeholder="مثال: 20"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:10px">
            <div class="input-grp"><label>الهدف</label><select id="n-goal"><option value="خسارة وزن" {{ old('goal') == 'خسارة وزن' ? 'selected' : '' }}>خسارة وزن</option><option value="زيادة كتلة عضلية" {{ old('goal') == 'زيادة كتلة عضلية' ? 'selected' : '' }}>زيادة كتلة عضلية</option><option value="تنشيف" {{ old('goal') == 'تنشيف' ? 'selected' : '' }}>تنشيف</option><option value="لياقة عامة" {{ old('goal') == 'لياقة عامة' ? 'selected' : '' }}>لياقة عامة</option></select></div>
            <div class="input-grp"><label>مستوى النشاط</label><select id="n-activity"><option value="خفيف" {{ old('activity_level') == 'خفيف' ? 'selected' : '' }}>خفيف</option><option value="متوسط" {{ old('activity_level', 'متوسط') == 'متوسط' ? 'selected' : '' }}>متوسط</option><option value="مكثف" {{ old('activity_level') == 'مكثف' ? 'selected' : '' }}>مكثف</option></select></div>
            <div class="input-grp"><label>أيام التمرين/أسبوع</label><input id="n-days" type="number" value="{{ old('workout_days', 4) }}"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px">
            <div class="input-grp"><label>الوظيفة</label><input id="n-job" type="text" value="{{ old('job', $member->memberProfile->job ?? '') }}" placeholder="مثال: موظف مكتب"></div>
        </div>
        <button type="button" class="btn btn-ai" onclick="generateNutrition()"><i class="ti ti-sparkles"></i> إنشاء النظام الغذائي بالذكاء الاصطناعي</button>
    </form>
</div>

<div id="nutrition-output"></div>

<div id="send-section" style="display:none;margin-top:1rem">
    <div class="card">
        <div style="display:flex;gap:8px;align-items:center">
            <button class="btn btn-primary" onclick="savePlan()"><i class="ti ti-send"></i> إرسال إلى {{ $member->name }}</button>
            <span id="save-status" style="font-size:12px;color:var(--color-text-secondary)"></span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPlan = null;

function generateNutrition(){
    const data = {
        name: document.getElementById('n-name').value,
        age: document.getElementById('n-age').value,
        weight: document.getElementById('n-weight').value,
        height: document.getElementById('n-height').value,
        wrist: document.getElementById('n-wrist').value,
        waist: document.getElementById('n-waist').value,
        goal: document.getElementById('n-goal').value,
        activity_level: document.getElementById('n-activity').value,
        body_fat: document.getElementById('n-fat').value,
        workout_days: document.getElementById('n-days').value,
        job: document.getElementById('n-job').value,
        _token: '{{ csrf_token() }}'
    };

    const out = document.getElementById('nutrition-output');
    out.innerHTML = `<div style="text-align:center;padding:2rem;color:var(--color-text-secondary)"><div class="spinner" style="border-color:#534AB7;border-top-color:transparent;display:inline-block;width:24px;height:24px;border:2px solid rgba(83,74,183,0.3);border-top-color:#534AB7;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-left:8px"></div> الذكاء الاصطناعي يحسب النظام الغذائي...</div>`;
    document.getElementById('send-section').style.display = 'none';

    fetch('{{ route('ai.generate-nutrition') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(plan => {
        if(plan.error) { out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">${plan.error}</div>`; return; }
        currentPlan = plan;
        currentPlan.member_id = {{ $member->id }};
        renderPlan(plan);
        document.getElementById('send-section').style.display = 'block';
    })
    .catch(e => {
        out.innerHTML = `<div style="color:#A32D2D;font-size:13px;padding:1rem">حدث خطأ: ${e.message}</div>`;
    });
}

function renderPlan(plan){
    const out = document.getElementById('nutrition-output');
    let html = `<div style="margin-top:1rem">
    <div style="background:#EEEDFE;border:0.5px solid #AFA9EC;border-radius:12px;padding:1.25rem;margin-bottom:1rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem">
            <div style="font-size:14px;font-weight:500;color:#3C3489"><i class="ti ti-sparkles" style="color:#534AB7"></i> النظام الغذائي المقترح</div>
            <span style="font-size:12px;color:var(--color-text-tertiary)">قابل للتعديل</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-bottom:1rem">
            <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">السعرات</div><div style="font-size:20px;font-weight:500;color:#534AB7">${plan.calories}</div></div>
            <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">بروتين</div><div style="font-size:20px;font-weight:500;color:#1D9E75">${plan.protein}غ</div></div>
            <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">كارب</div><div style="font-size:20px;font-weight:500;color:#185FA5">${plan.carbs}غ</div></div>
            <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">دهون</div><div style="font-size:20px;font-weight:500;color:#854F0B">${plan.fat}غ</div></div>
            <div style="background:var(--color-background-primary);border-radius:8px;padding:10px;text-align:center"><div style="font-size:11px;color:var(--color-text-secondary)">ألياف</div><div style="font-size:20px;font-weight:500;color:#0F6E56">${plan.fiber||0}غ</div></div>
        </div>
        ${plan.water ? `<div style="background:var(--color-background-primary);border-radius:8px;padding:8px 12px;margin-bottom:1rem;font-size:12px;color:#185FA5"><i class="ti ti-droplet"></i> الماء: ${plan.water}</div>` : ''}
    </div>
    <div style="display:grid;gap:10px">`;

    (plan.meals||[]).forEach((m, i) => {
        html += `<div class="card" style="padding:0;overflow:hidden;border-right:4px solid ${['#534AB7','#1D9E75','#185FA5','#854F0B','#A32D2D'][i%5]}">
            <div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;background:var(--color-background-secondary)">
                <div><span style="font-size:14px;font-weight:500">${m.name}</span> <span style="font-size:11px;color:var(--color-text-tertiary)">${m.time||''}</span></div>
                <div style="display:flex;gap:8px;align-items:center">
                    <span class="badge badge-green"><span id="mcals-${i}">${m.calories}</span> سعرة</span>
                    <button class="btn" style="padding:2px 8px;font-size:11px" onclick="toggleEdit(${i})"><i class="ti ti-edit"></i></button>
                </div>
            </div>
            <div id="meal-view-${i}" style="padding:10px 14px">
                <div style="display:flex;gap:10px;margin-bottom:6px">
                    <span style="font-size:11px;background:#EEEDFE;padding:2px 8px;border-radius:4px;color:#534AB7">بروتين: <span id="mprotein-${i}">${m.protein||0}</span>غ</span>
                    <span style="font-size:11px;background:#E6F1FB;padding:2px 8px;border-radius:4px;color:#185FA5">كارب: <span id="mcarbs-${i}">${m.carbs||0}</span>غ</span>
                    <span style="font-size:11px;background:#FAEEDA;padding:2px 8px;border-radius:4px;color:#854F0B">دهون: <span id="mfat-${i}">${m.fat||0}</span>غ</span>
                </div>
                <div style="font-size:12px;color:var(--color-text-secondary);margin-bottom:6px"><span id="mitems-${i}">${(m.items||[]).join(' • ')}</span></div>
                ${(m.vitamins||[]).length ? '<div style="font-size:11px;color:#0F6E56;margin-bottom:2px"><i class="ti ti-vitamin" style="font-size:10px"></i> فيتامينات: ' + m.vitamins.map(v => v.name + ' (' + v.amount + ')').join(' • ') + '</div>' : ''}
                ${(m.minerals||[]).length ? '<div style="font-size:11px;color:#854F0B"><i class="ti ti-flask" style="font-size:10px"></i> معادن: ' + m.minerals.map(v => v.name + ' (' + v.amount + ')').join(' • ') + '</div>' : ''}
            </div>
            <div id="meal-edit-${i}" style="display:none;padding:10px 14px;background:var(--color-background-primary)">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:6px;margin-bottom:6px">
                    <div class="input-grp"><label style="font-size:11px">السعرات</label><input type="number" id="edit-cals-${i}" value="${m.calories}" style="padding:6px 8px;font-size:12px"></div>
                    <div class="input-grp"><label style="font-size:11px">بروتين</label><input type="number" id="edit-protein-${i}" value="${m.protein||0}" style="padding:6px 8px;font-size:12px"></div>
                    <div class="input-grp"><label style="font-size:11px">كارب</label><input type="number" id="edit-carbs-${i}" value="${m.carbs||0}" style="padding:6px 8px;font-size:12px"></div>
                    <div class="input-grp"><label style="font-size:11px">دهون</label><input type="number" id="edit-fat-${i}" value="${m.fat||0}" style="padding:6px 8px;font-size:12px"></div>
                </div>
                <div class="input-grp" style="margin-bottom:6px"><label style="font-size:11px">المكونات (كل مكون في سطر)</label><textarea id="edit-items-${i}" rows="2" style="padding:6px 8px;font-size:12px">${(m.items||[]).join('\n')}</textarea></div>
                <div style="display:flex;gap:6px;justify-content:end">
                    <button class="btn" style="padding:4px 12px;font-size:11px" onclick="saveEdit(${i})"><i class="ti ti-check"></i> حفظ</button>
                    <button class="btn" style="padding:4px 12px;font-size:11px;color:var(--color-text-secondary)" onclick="cancelEdit(${i})">إلغاء</button>
                </div>
            </div>
        </div>`;
    });

    html += `</div>
    ${plan.notes ? `<div style="margin-top:10px;background:#FAEEDA;border-right:3px solid #854F0B;border-radius:8px;padding:10px 14px;font-size:12px;color:#854F0B"><i class="ti ti-bulb"></i> ${plan.notes}</div>` : ''}
    ${plan.alternatives ? `<div style="margin-top:8px;font-size:12px;color:var(--color-text-secondary)">بدائل: ${plan.alternatives.join(' • ')}</div>` : ''}
    </div>`;

    out.innerHTML = html;
}

function toggleEdit(i){
    document.getElementById('meal-view-' + i).style.display = 'none';
    document.getElementById('meal-edit-' + i).style.display = 'block';
}

function cancelEdit(i){
    document.getElementById('meal-view-' + i).style.display = 'block';
    document.getElementById('meal-edit-' + i).style.display = 'none';
}

function saveEdit(i){
    const meal = currentPlan.meals[i];
    const cals = parseInt(document.getElementById('edit-cals-' + i).value) || 0;
    const protein = parseInt(document.getElementById('edit-protein-' + i).value) || 0;
    const carbs = parseInt(document.getElementById('edit-carbs-' + i).value) || 0;
    const fat = parseInt(document.getElementById('edit-fat-' + i).value) || 0;
    const items = document.getElementById('edit-items-' + i).value.split('\n').filter(x => x.trim());

    meal.calories = cals;
    meal.protein = protein;
    meal.carbs = carbs;
    meal.fat = fat;
    meal.items = items;

    document.getElementById('mcals-' + i).textContent = cals;
    document.getElementById('mprotein-' + i).textContent = protein;
    document.getElementById('mcarbs-' + i).textContent = carbs;
    document.getElementById('mfat-' + i).textContent = fat;
    document.getElementById('mitems-' + i).textContent = items.join(' • ');

    // Recalculate totals
    let totalCals = 0, totalProtein = 0, totalCarbs = 0, totalFat = 0;
    currentPlan.meals.forEach(m => {
        totalCals += m.calories || 0;
        totalProtein += m.protein || 0;
        totalCarbs += m.carbs || 0;
        totalFat += m.fat || 0;
    });
    currentPlan.calories = totalCals;
    currentPlan.protein = totalProtein;
    currentPlan.carbs = totalCarbs;
    currentPlan.fat = totalFat;

    // Update the summary
    const out = document.getElementById('nutrition-output');
    const summary = out.querySelector('[style*="background:#EEEDFE"]');
    if(summary) summary.outerHTML = summary.outerHTML; // crude re-render not needed, just update values
    // Simple approach: rebuild the summary numbers
    const calsEl = summary?.querySelector('[style*="color:#534AB7"]');
    if(calsEl) calsEl.textContent = totalCals;

    cancelEdit(i);
}

function savePlan(){
    if(!currentPlan) return;
    const btn = document.querySelector('#send-section .btn-primary');
    const status = document.getElementById('save-status');
    btn.disabled = true;
    status.textContent = 'جاري الحفظ...';

    fetch('{{ route('nutrition.save') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({
            member_id: currentPlan.member_id,
            goal: document.getElementById('n-goal').value,
            daily_calories: currentPlan.calories,
            protein: currentPlan.protein,
            carbs: currentPlan.carbs,
            fat: currentPlan.fat,
            fiber: currentPlan.fiber || 0,
            water: currentPlan.water || '',
            notes: currentPlan.notes || '',
            meals: currentPlan.meals.map(m => ({
                name: m.name,
                time: m.time || '',
                calories: m.calories || 0,
                protein: m.protein || 0,
                carbs: m.carbs || 0,
                fat: m.fat || 0,
                items: (m.items||[]).join('\n'),
                vitamins: (m.vitamins||[]).map(v => `${v.name} (${v.amount})`).join(' • '),
                minerals: (m.minerals||[]).map(v => `${v.name} (${v.amount})`).join(' • ')
            }))
        })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            status.innerHTML = '<span style="color:#1D9E75">تم الإرسال بنجاح ✅</span>';
            btn.textContent = 'تم الإرسال';
        } else {
            status.innerHTML = '<span style="color:#A32D2D">خطأ في الحفظ</span>';
            btn.disabled = false;
        }
    })
    .catch(e => {
        status.innerHTML = '<span style="color:#A32D2D">خطأ: ' + e.message + '</span>';
        btn.disabled = false;
    });
}
</script>
<style>
.spinner { display:inline-block; width:20px; height:20px; border:2px solid rgba(83,74,183,0.3); border-top-color:#534AB7; border-radius:50%; animation:spin .7s linear infinite; vertical-align:middle; margin-left:6px; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>
@endpush
