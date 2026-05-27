<?php
echo "<pre>=== Fitness Platform Auto-Fix ===\n\n";

// 1. Bootstrap Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// 2. Fix routes/web.php
echo "1. routes/web.php... ";
$correct = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/routes/web.php');
if ($correct) {
    file_put_contents(__DIR__ . '/../routes/web.php', $correct);
    $r = `php -l "` . __DIR__ . '/../routes/web.php' . `" 2>&1`;
    echo strpos($r, 'No syntax') !== false ? "OK\n" : "SYNTAX ERROR\n$r\n";
} else echo "DOWNLOAD FAILED\n";

// 3. Download & create missing files from GitHub
echo "\n2. Downloading missing files...\n";
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
];
foreach ($files as $f) {
    $local = __DIR__ . '/../' . $f;
    $dir = dirname($local);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $content = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/' . $f);
    if ($content) {
        file_put_contents($local, $content);
        echo "  ✅ $f\n";
    } else {
        echo "  ❌ $f (download failed)\n";
    }
}

// 4. Create appointments table if not exists
echo "\n3. Appointments table... ";
if (!Schema::hasTable('appointments')) {
    Schema::create('appointments', function ($table) {
        $table->id();
        $table->unsignedBigInteger('doctor_id');
        $table->unsignedBigInteger('member_id');
        $table->dateTime('scheduled_at');
        $table->integer('duration_minutes')->default(30);
        $table->string('status', 20)->default('pending');
        $table->text('notes')->nullable();
        $table->text('cancellation_reason')->nullable();
        $table->timestamps();
        $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
    });
    echo "created\n";
} else echo "exists\n";

// 5. Fix app.blade.php sidebar (add doctors + appointments links)
echo "\n4. app.blade.php sidebar... ";
$layout = __DIR__ . '/../resources/views/layouts/app.blade.php';
$c = file_get_contents($layout);

$changes = 0;

// Add doctors link after trainers in sidebar (admin & super_admin sections)
$c = str_replace(
    '<a href="{{ route(\'admin.trainers\') }}" class="nav-item {{ $r(\'admin.trainers\') ? \'active-page\' : \'\' }}"><i class="ti ti-user-star"></i> المدربون</a>
            <a href="{{ route(\'admin.gyms\') }}" class="nav-item {{ $r(\'admin.gyms\') ? \'active-page\' : \'\' }}"><i class="ti ti-building"></i> الصالات</a>',
    '<a href="{{ route(\'admin.trainers\') }}" class="nav-item {{ $r(\'admin.trainers\') ? \'active-page\' : \'\' }}"><i class="ti ti-user-star"></i> المدربون</a>
            <a href="{{ route(\'admin.doctors\') }}" class="nav-item {{ $r(\'admin.doctors\') ? \'active-page\' : \'\' }}"><i class="ti ti-stethoscope"></i> الأطباء</a>
            <a href="{{ route(\'admin.gyms\') }}" class="nav-item {{ $r(\'admin.gyms\') ? \'active-page\' : \'\' }}"><i class="ti ti-building"></i> الصالات</a>',
    $c
);

// Add appointments link for doctor in bottomItems
if (strpos($c, 'doctor.appointments') === false) {
    $c = str_replace(
        "['route' => 'doctor.dashboard', 'icon' => 'ti ti-stethoscope', 'label' => 'عيادتي'],\n        ['route' => 'doctor.patients', 'icon' => 'ti ti-users', 'label' => 'مرضاي'],",
        "['route' => 'doctor.dashboard', 'icon' => 'ti ti-stethoscope', 'label' => 'عيادتي'],\n        ['route' => 'doctor.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],\n        ['route' => 'doctor.patients', 'icon' => 'ti ti-users', 'label' => 'مرضاي'],",
        $c
    );
    $changes++;
}

// Add appointments link for member in moreItems
if (strpos($c, 'member.appointments') === false) {
    $c = str_replace(
        "['route' => 'member.food-analyzer', 'icon' => 'ti ti-search', 'label' => 'محلل الطعام'],",
        "['route' => 'member.appointments', 'icon' => 'ti ti-calendar', 'label' => 'المواعيد'],\n        ['route' => 'member.food-analyzer', 'icon' => 'ti ti-search', 'label' => 'محلل الطعام'],",
        $c
    );
    $changes++;
}

// Add doctors to moreItems for admin
if (strpos($c, "'admin.doctors'") === false || strpos($c, "'admin.doctors'") !== false) {
    $c = str_replace(
        "['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],\n        ['route' => 'admin.revenue', 'icon' => 'ti ti-chart-bar', 'label' => 'الأرباح'],",
        "['route' => 'admin.doctors', 'icon' => 'ti ti-stethoscope', 'label' => 'الأطباء'],\n        ['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],\n        ['route' => 'admin.revenue', 'icon' => 'ti ti-chart-bar', 'label' => 'الأرباح'],",
        $c
    );
    $c = str_replace(
        "['route' => 'admin.trainers', 'icon' => 'ti ti-user-star', 'label' => 'المدربون'],\n        ['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],",
        "['route' => 'admin.trainers', 'icon' => 'ti ti-user-star', 'label' => 'المدربون'],\n        ['route' => 'admin.doctors', 'icon' => 'ti ti-stethoscope', 'label' => 'الأطباء'],\n        ['route' => 'admin.gyms', 'icon' => 'ti ti-building', 'label' => 'الصالات'],",
        $c
    );
    $changes++;
}

file_put_contents($layout, $c);
echo $changes > 0 ? "updated ($changes changes)\n" : "already up-to-date\n";

// 6. Clear view cache
echo "\n5. View cache... ";
$cacheDir = __DIR__ . '/../storage/framework/views';
if (is_dir($cacheDir)) { array_map('unlink', glob($cacheDir . '/*')); echo "cleared\n"; } else echo "not found\n";

echo "\n=== Done! ===";
echo "\nالروابط الجديدة:";
echo "\n- admin/doctors (الادارة → الأطباء)";
echo "\n- doctor/appointments (دكتور → المواعيد)";
echo "\n- member/appointments (عضو → المواعيد)";
echo "\n\nhttps://busnisscard.com/platform/public/</pre>";
