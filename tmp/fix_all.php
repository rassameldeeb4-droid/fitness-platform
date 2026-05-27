<?php
// Self-update: always download latest version from GitHub
$self = __FILE__;
$url = 'https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/tmp/fix_all.php?_=' . time();
$latest = @file_get_contents($url);
if ($latest) {
    $current = file_exists($self) ? md5(file_get_contents($self)) : '';
    $newHash = md5($latest);
    if ($newHash !== $current) {
        file_put_contents($self, $latest);
        echo "🔄 Self-updated to latest version\n\n";
        eval('?>' . $latest);
        exit;
    }
}

// If we get here, we're already the latest version
echo "<pre>=== Fitness Platform Auto-Fix ===\n\n";

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;

// Download & create ALL missing files from GitHub
echo "Downloading missing files...\n";
$files = [
    'app/Http/Controllers/Admin/DoctorController.php',
    'app/Http/Controllers/Doctor/AppointmentController.php',
    'app/Http/Controllers/Member/AppointmentController.php',
    'app/Models/Appointment.php',
    'resources/views/admin/doctors.blade.php',
    'resources/views/admin/doctor-create.blade.php',
    'resources/views/admin/doctor-edit.blade.php',
    'resources/views/admin/doctor-show.blade.php',
    'resources/views/admin/doctor-patients.blade.php',
    'resources/views/doctor/appointments.blade.php',
    'resources/views/doctor/appointment-create.blade.php',
    'resources/views/doctor/appointment-show.blade.php',
    'resources/views/member/appointments.blade.php',
    'resources/views/doctor/dashboard.blade.php',
    'database/migrations/2026_05_27_000001_create_appointments_table.php',
    'routes/web.php',
    'app/Http/Controllers/Admin/TrainerController.php',
    'resources/views/admin/trainer-create.blade.php',
    'resources/views/admin/trainer-edit.blade.php',
    'resources/views/admin/trainer-trainees.blade.php',
    'resources/views/admin/exercises/create.blade.php',
    'resources/views/admin/exercises/edit.blade.php',
];
$ok = 0; $fail = 0;
foreach ($files as $f) {
    $local = __DIR__ . '/../' . $f;
    $dir = dirname($local);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $content = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/' . $f);
    if ($content) {
        file_put_contents($local, $content);
        echo "  ✅ $f\n"; $ok++;
    } else {
        echo "  ❌ $f\n"; $fail++;
    }
}
echo "\n$ok files created/updated, $fail failed\n";

// Create appointments table
echo "\nAppointments table... ";
if (!Schema::hasTable('appointments')) {
    Schema::create('appointments', function ($t) {
        $t->id();
        $t->unsignedBigInteger('doctor_id');
        $t->unsignedBigInteger('member_id');
        $t->dateTime('scheduled_at');
        $t->integer('duration_minutes')->default(30);
        $t->string('status', 20)->default('pending');
        $t->text('notes')->nullable();
        $t->text('cancellation_reason')->nullable();
        $t->timestamps();
        $t->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        $t->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
    });
    echo "created ✅\n";
} else echo "exists ✅\n";

// Fix app.blade.php sidebar links
echo "\nSidebar links... ";
$layout = __DIR__ . '/../resources/views/layouts/app.blade.php';
$c = file_get_contents($layout);
$ch = 0;

// Doctors in sidebar nav
if (strpos($c, 'admin.doctors') === false) {
    $c = str_replace(
        '<a href="{{ route(\'admin.trainers\') }}" class="nav-item {{ $r(\'admin.trainers\') ? \'active-page\' : \'\' }}"><i class="ti ti-user-star"></i> المدربون</a>',
        '<a href="{{ route(\'admin.trainers\') }}" class="nav-item {{ $r(\'admin.trainers\') ? \'active-page\' : \'\' }}"><i class="ti ti-user-star"></i> المدربون</a>
            <a href="{{ route(\'admin.doctors\') }}" class="nav-item {{ $r(\'admin.doctors\') ? \'active-page\' : \'\' }}"><i class="ti ti-stethoscope"></i> الأطباء</a>',
        $c
    );
    $ch++;
}

// Doctor appointments in bottomItems
if (strpos($c, 'doctor.appointments') === false) {
    $c = str_replace(
        "'doctor.patients', 'icon' => 'ti ti-users', 'label' => 'مرضاي'",
        "'doctor.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],\n        ['route' => 'doctor.patients', 'icon' => 'ti ti-users', 'label' => 'مرضاي'",
        $c
    );
    $ch++;
}

// Member appointments in moreItems
if (strpos($c, 'member.appointments') === false) {
    $c = str_replace(
        "'member.food-analyzer', 'icon' => 'ti ti-search', 'label' => 'محلل الطعام'",
        "'member.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],\n        ['route' => 'member.food-analyzer', 'icon' => 'ti ti-search', 'label' => 'محلل الطعام'",
        $c
    );
    $ch++;
}

file_put_contents($layout, $c);
echo $ch > 0 ? "updated ($ch changes)\n" : "already ok\n";

// Clear view cache
$cache = __DIR__ . '/../storage/framework/views';
if (is_dir($cache)) { array_map('unlink', glob($cache . '/*')); echo "\nView cache cleared\n"; }

echo "\n=== ✅ Done! ===";
echo "\nAdmin: /admin/doctors (إدارة الأطباء + إضافة/تعديل/حذف)";
echo "\nDoctor: /doctor/appointments (إدارة المواعيد)";
echo "\nMember: /member/appointments (عرض المواعيد)";
echo "\n\nhttps://busnisscard.com/platform/public/</pre>";
