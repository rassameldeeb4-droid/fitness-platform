<?php
echo "<pre>Fixing routes/web.php...\n";

// Download correct web.php from GitHub
$url = 'https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/routes/web.php';
$correct = @file_get_contents($url);
if (!$correct) die("ERROR: Cannot download from GitHub\n");

$path = __DIR__ . '/../routes/web.php';
if (file_put_contents($path, $correct) === false) die("ERROR: Cannot write file\n");
echo "routes/web.php restored from GitHub\n";

// Verify syntax
$result = `php -l "$path" 2>&1`;
echo "$result\n";

// Ensure DoctorController exists
$ctrl = __DIR__ . '/../app/Http/Controllers/Admin/DoctorController.php';
if (!file_exists($ctrl)) {
    file_put_contents($ctrl, "<?php\n\nnamespace App\Http\Controllers\Admin;\n\nuse App\Http\Controllers\Controller;\nuse App\Models\User;\n\nclass DoctorController extends Controller\n{\n    public function index()\n    {\n        \$doctors = User::where('role', 'doctor')->paginate(15);\n        return view('admin.doctors', compact('doctors'));\n    }\n}");
    echo "DoctorController.php created\n";
} else echo "DoctorController.php exists\n";

// Ensure view exists
$view = __DIR__ . '/../resources/views/admin/doctors.blade.php';
if (!file_exists($view)) {
    $blade = "@extends('layouts.app')\n@section('title', 'الأطباء')\n@section('content')\n<div class=\"page-title\">إدارة الأطباء</div>\n<div class=\"stat-grid\" style=\"grid-template-columns:repeat(2,1fr)\">\n    <div class=\"stat-card\"><div class=\"stat-label\">إجمالي الأطباء</div><div class=\"stat-value\">{{ \$doctors->total() }}</div></div>\n    <div class=\"stat-card\"><div class=\"stat-label\">الأطباء النشطون</div><div class=\"stat-value\" style=\"color:#1D9E75\">{{ \$doctors->count() }}</div></div>\n</div>\n<div class=\"card\" style=\"padding:0\">\n    <table>\n        <thead><tr><th>الطبيب</th><th>البريد</th><th>الجوال</th><th>تاريخ التسجيل</th></tr></thead>\n        <tbody>\n            @forelse(\$doctors as \$doc)\n            <tr>\n                <td><div style=\"display:flex;align-items:center;gap:8px\"><div class=\"avatar\" style=\"width:36px;height:36px;background:#E8F5E9;color:#1D9E75;font-size:12px\">{{ substr(\$doc->name, 0, 1) }}</div>{{ \$doc->name }}</div></td>\n                <td>{{ \$doc->email }}</td>\n                <td>{{ \$doc->phone ?? '—' }}</td>\n                <td style=\"font-size:12px;color:var(--color-text-secondary)\">{{ \$doc->created_at->format('Y-m-d') }}</td>\n            </tr>\n            @empty\n            <tr><td colspan=\"4\" style=\"text-align:center;color:var(--color-text-secondary)\">لا يوجد أطباء</td></tr>\n            @endforelse\n        </tbody>\n    </table>\n</div>\n@endsection";
    file_put_contents($view, $blade);
    echo "doctors.blade.php created\n";
} else echo "doctors.blade.php exists\n";

// Clear caches
$cache = __DIR__ . '/../storage/framework/views';
if (is_dir($cache)) { array_map('unlink', glob($cache . '/*')); echo "View cache cleared\n"; }

echo "\nDone! جرب الآن: https://busnisscard.com/platform/public/</pre>";
