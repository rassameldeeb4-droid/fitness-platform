@extends('layouts.app')
@section('title', 'محلل الطعام')
@section('content')
<div class="page-title"><i class="ti ti-search" style="color:#534AB7"></i> محلل الطعام</div>
<div class="card">
    <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px">ابحث عن أي طعام واحصل على معلومات غذائية</div>
    <div style="display:flex;gap:8px;margin-bottom:10px">
        <input id="food-q" placeholder="مثال: 100 غرام أرز أبيض مسلوق..." style="flex:1;padding:10px 14px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;font-family:var(--font-sans);outline:none">
        <button class="btn btn-ai" id="food-btn" onclick="analyzeFood()"><i class="ti ti-search"></i> تحليل</button>
    </div>
</div>
<div id="food-result"></div>
<script>
async function analyzeFood(){
    const q = document.getElementById('food-q').value.trim(); if(!q) return;
    const res = document.getElementById('food-result'); const btn = document.getElementById('food-btn');
    btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> جاري التحليل...';
    res.innerHTML = '<div style="text-align:center;padding:2rem">جاري التحليل...</div>';
    try {
        const resp = await fetch('{{ route('foods.analyze-post') }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({query: q})
        });
        const f = await resp.json();
        btn.disabled = false; btn.innerHTML = '<i class="ti ti-search"></i> تحليل';
        if(f.error) { res.innerHTML = '<div style="color:#A32D2D;padding:1rem">'+f.error+'</div>'; return; }
        res.innerHTML = '<div style="background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:1.25rem;margin-top:1rem"><div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem"><div><div style="font-size:18px;font-weight:500">'+(f.food||'')+'</div><div style="font-size:13px;color:var(--color-text-secondary)">'+(f.amount||'')+'</div></div><div style="text-align:center;background:#E1F5EE;border-radius:12px;padding:12px 20px"><div style="font-size:32px;font-weight:500;color:#1D9E75">'+(f.calories||0)+'</div><div style="font-size:12px;color:#0F6E56">سعرة</div></div></div><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">'+(f.protein!==undefined?'<div style="background:#EEEDFE;border-radius:8px;padding:10px;text-align:center"><div style="font-size:16px;font-weight:500;color:#534AB7">'+f.protein+'غ</div><div style="font-size:11px;color:#534AB7">بروتين</div></div>':'')+(f.carbs!==undefined?'<div style="background:#E6F1FB;border-radius:8px;padding:10px;text-align:center"><div style="font-size:16px;font-weight:500;color:#185FA5">'+f.carbs+'غ</div><div style="font-size:11px;color:#185FA5">كارب</div></div>':'')+(f.fat!==undefined?'<div style="background:#FAEEDA;border-radius:8px;padding:10px;text-align:center"><div style="font-size:16px;font-weight:500;color:#854F0B">'+f.fat+'غ</div><div style="font-size:11px;color:#854F0B">دهون</div></div>':'')+'</div>'+(f.tips?'<div style="margin-top:1rem;background:#E1F5EE;border-radius:8px;padding:10px 14px;border-right:3px solid #1D9E75;font-size:13px;color:#0F6E56"><i class="ti ti-bulb"></i> '+f.tips+'</div>':'')+'</div>';
    } catch(e) { btn.disabled = false; btn.innerHTML = '<i class="ti ti-search"></i> تحليل'; res.innerHTML = '<div style="color:#A32D2D;padding:1rem">خطأ: '+e.message+'</div>'; }
}
</script>
@endsection
