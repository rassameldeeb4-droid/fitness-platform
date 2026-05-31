<?php
// Self-update: always try to get latest version from GitHub
$self = __FILE__;
$ref = 'main';
$url = 'https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/' . $ref . '/tmp/fix_all.php?_=' . time();
$latest = @file_get_contents($url);
$downloaded = $latest !== false;
if ($latest) {
    $localContent = file_exists($self) ? file_get_contents($self) : '';
    $newHash = md5($latest);
    $oldHash = md5($localContent);
    if ($newHash !== $oldHash) {
        file_put_contents($self, $latest);
        echo "🔄 Self-updated (new hash: $newHash)\n\n";
        eval('?>' . $latest);
        exit;
    }
}

// If we get here, we're already the latest version
echo "<pre>=== Fitness Platform Auto-Fix ===\n";
echo "Version: v1.4 | " . date('Y-m-d H:i') . "\n\n";

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
    'app/Http/Controllers/Admin/ExerciseController.php',
    'tmp/fix_storage.php',
    'tmp/showlog.php',
    'tmp/setkey.php',
    'tmp/addkey.php',
    'tmp/opclear.php',
    'tmp/accounts.php',
    'app/Services/AiService.php',
    'config/services.php',
    'app/Http/Controllers/FoodController.php',
    'app/Http/Controllers/Member/NutritionController.php',
    'resources/views/foods/analyze.blade.php',
    'resources/views/member/nutrition.blade.php',
    'database/migrations/2026_05_29_000001_add_body_metrics_to_member_profiles.php',
    'app/Http/Controllers/NutritionSaveController.php',
    'app/Http/Controllers/AiController.php',
    'resources/views/trainer/nutrition-create.blade.php',
    'resources/views/trainer/whatsapp.blade.php',
    'resources/views/admin/doctors.blade.php',
    'app/Http/Controllers/Trainer/WhatsAppController.php',
    'app/Models/TrainerWhatsAppConfig.php',
    'database/migrations/2026_05_31_000001_create_trainer_whatsapp_configs_table.php',
    'routes/web.php',
    'tmp/deploy_fitcure.php',
    'tmp/fix_subdir.php',
    'tmp/fix_fitcure.php',
    'tmp/find_fitcure_root.php',
    'tmp/deploy_fitcure_v2.php',
    'tmp/fitcure_http_test.php',
    'tmp/fitcure_diag.php',
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

// Add body metrics columns to member_profiles
echo "\nBody metrics... ";
if (!Schema::hasColumn('member_profiles', 'wrist_circumference')) {
    Schema::table('member_profiles', function ($t) {
        $t->decimal('wrist_circumference', 5, 2)->nullable()->after('target_weight');
        $t->decimal('waist_circumference', 5, 2)->nullable()->after('wrist_circumference');
        $t->string('job')->nullable()->after('complaints');
    });
    echo "added ✅\n";
} else echo "exists ✅\n";

// WhatsApp config table
echo "\nWhatsApp config... ";
if (!Schema::hasTable('trainer_whatsapp_configs')) {
    Schema::create('trainer_whatsapp_configs', function ($t) {
        $t->id();
        $t->unsignedBigInteger('trainer_id')->unique();
        $t->string('server_url', 255)->nullable();
        $t->string('api_key', 100)->nullable();
        $t->string('phone_number', 20)->nullable();
        $t->boolean('is_connected')->default(false);
        $t->boolean('notify_nutrition')->default(true);
        $t->boolean('notify_workout')->default(true);
        $t->boolean('notify_progress')->default(false);
        $t->timestamps();
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

// Clear view & route cache
$cache = __DIR__ . '/../storage/framework/views';
if (is_dir($cache)) { array_map('unlink', glob($cache . '/*')); echo "View cache cleared\n"; }
foreach (glob(__DIR__ . '/../bootstrap/cache/*.php') as $f) { @unlink($f); echo "Removed: " . basename($f) . "\n"; }

// Ensure OPENAI_API_KEY in .env
$envFile = __DIR__ . '/../.env';
$envContent = file_exists($envFile) ? file_get_contents($envFile) : '';
if (!str_contains($envContent, 'OPENAI_API_KEY=')) {
    echo "⚠️  OPENAI_API_KEY not set. Set it manually in .env\n";
} else echo "OPENAI_API_KEY present in .env\n";

// Ensure storage symlink
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/../public/storage';
if (!file_exists($link) && is_dir($target)) {
    symlink($target, $link);
    echo "Storage symlink created\n";
} elseif (!file_exists($link)) {
    echo "Warning: storage/app/public not found\n";
} else echo "Storage symlink exists\n";

// Clear opcache so updated files are fresh
if (function_exists('opcache_reset')) {
    opcache_reset();
    // Also invalidate each downloaded file
    if (function_exists('opcache_invalidate')) {
        foreach ($files as $f) {
            $local = __DIR__ . '/../' . $f;
            if (file_exists($local)) {
                opcache_invalidate($local, true);
            }
        }
    }
    echo "Opcache cleared + files invalidated\n";
}

// Also fix fitcure subdirectory
echo "\n=== Fitcure subdirectory check ===\n";
$fitcureTarget = '/home/busnisscard/public_html/fitcure';
$fitcureEnv = "$fitcureTarget/.env";
$fitcureHtaccess = "$fitcureTarget/public/.htaccess";
$fitcureHtContent = @file_get_contents($fitcureHtaccess);

// Ensure APP_KEY + APP_DEBUG
if (file_exists($fitcureEnv)) {
    $envContent = file_get_contents($fitcureEnv);
    $envDirty = false;
    if (!preg_match('/APP_KEY=base64:/', $envContent)) {
        $key = 'base64:' . base64_encode(random_bytes(32));
        $envContent = preg_match('/APP_KEY=.+/', $envContent)
            ? preg_replace('/APP_KEY=.+/', "APP_KEY=$key", $envContent)
            : str_replace('APP_KEY=', "APP_KEY=$key", $envContent);
        $envDirty = true;
        echo "  APP_KEY set\n";
    }
    if (str_contains($envContent, 'APP_DEBUG=false')) {
        $envContent = str_replace('APP_DEBUG=false', 'APP_DEBUG=true', $envContent);
        $envDirty = true;
        echo "  APP_DEBUG enabled\n";
    }
    if ($envDirty) file_put_contents($fitcureEnv, $envContent);
}
if ($fitcureHtContent && !str_contains($fitcureHtContent, 'RewriteBase')) {
    $fitcureHtContent = str_replace(
        'RewriteEngine On',
        "RewriteEngine On\nRewriteBase /fitcure/public/",
        $fitcureHtContent
    );
    file_put_contents($fitcureHtaccess, $fitcureHtContent);
    echo "  .htaccess RewriteBase fixed\n";
}

// Fix vendor/composer directory (missing autoload_real.php)
$vendorComposerSrc = dirname(__DIR__) . '/vendor/composer';
$vendorComposerDst = "$fitcureTarget/vendor/composer";
if (is_dir($vendorComposerSrc) && (!is_dir($vendorComposerDst) || !file_exists("$vendorComposerDst/autoload_real.php"))) {
    echo "  Copying vendor/composer... ";
    $copied = 0;
    @mkdir($vendorComposerDst, 0755, true);
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($vendorComposerSrc, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $f) {
        $dest = $vendorComposerDst . '/' . $it->getSubPathname();
        if ($f->isDir()) @mkdir($dest, 0755, true);
        else { @copy($f, $dest); $copied++; }
    }
    echo "$copied files\n";
}

// Check error_log
$fitcurePhpLog = "$fitcureTarget/error_log";
if (file_exists($fitcurePhpLog) && filesize($fitcurePhpLog) > 0) {
    $logLines = file($fitcurePhpLog);
    $last = array_slice($logLines, -5);
    echo "  PHP error_log (last 5):\n";
    foreach ($last as $l) { if (trim($l)) echo "    " . substr($l, 0, 400) . "\n"; }
} else {
    echo "  No PHP error_log found\n";
}

echo "\n=== ✅ Done! ===";
echo "\nAdmin: /admin/doctors (إدارة الأطباء + إضافة/تعديل/حذف)";
echo "\nDoctor: /doctor/appointments (إدارة المواعيد)";
echo "\nMember: /member/appointments (عرض المواعيد)";
echo "\n\nhttps://busnisscard.com/platform/public/</pre>";
