@extends('layouts.app')
@section('title', 'جدول المأكولات')
@push('styles')
<style>
.food-search{width:100%;padding:9px 14px;border-radius:var(--border-radius-md);border:0.5px solid var(--color-border-secondary);background:var(--color-background-primary);font-size:13px;font-family:var(--font-sans);margin-bottom:1rem;outline:none}
</style>
@endpush
@section('content')
<div class="page-title">جدول المأكولات الغذائي</div>
<input class="food-search" id="food-search" placeholder="ابحث عن مأكول..." oninput="filterFoods()">
<div class="food-cat-filter" style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:1rem" id="food-cats">
    @foreach($categories as $i => $cat)
    <span class="cat-pill {{ $i === 0 ? 'active' : '' }}" onclick="setFoodCat('{{ $cat }}', this)">{{ $cat }}</span>
    @endforeach
</div>
<div class="card" style="padding:0;overflow:auto">
<table>
<thead><tr><th>المأكول</th><th>الفئة</th><th>سعرات (100غ)</th><th>بروتين غ</th><th>كارب غ</th><th>دهون غ</th><th>ألياف غ</th></tr></thead>
<tbody id="food-tbody">
@foreach($foods as $f)
<tr class="food-row" data-cat="{{ $f->category }}" data-name="{{ $f->name }}">
    <td style="font-weight:500">{{ $f->name }}</td>
    <td><span class="badge badge-{{ $f->category === 'بروتين' ? 'blue' : ($f->category === 'كارب' ? 'amber' : ($f->category === 'دهون صحية' ? 'purple' : 'green')) }}">{{ $f->category }}</span></td>
    <td style="color:#1D9E75;font-weight:500">{{ $f->calories_per_100g }}</td>
    <td>{{ $f->protein_per_100g }}</td>
    <td>{{ $f->carbs_per_100g }}</td>
    <td>{{ $f->fat_per_100g }}</td>
    <td>{{ $f->fiber_per_100g }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<script>
let foodCat = 'الكل';
function setFoodCat(cat, el) {
    foodCat = cat;
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    filterFoods();
}
function filterFoods() {
    const q = (document.getElementById('food-search').value || '').toLowerCase();
    document.querySelectorAll('.food-row').forEach(r => {
        const show = (foodCat === 'الكل' || r.dataset.cat === foodCat) &&
            (r.dataset.name.includes(q));
        r.style.display = show ? '' : 'none';
    });
}
</script>
@endsection
