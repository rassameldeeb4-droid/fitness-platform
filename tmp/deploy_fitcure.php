<?php
// Deploy FitCore to fitcure.online (same cPanel account)
// This script determines the fitcure.online path and creates the platform

$busnisscardRoot = __DIR__; // /home/busnisscard/public_html/platform/tmp
$basePath = dirname(dirname($busnisscardRoot)); // /home/busnisscard/public_html

$possiblePaths = [
    $basePath . '/fitcure.online',
    dirname($basePath) . '/fitcure.online',
];

$fitcurePath = null;
foreach ($possiblePaths as $p) {
    if (is_dir($p) || @mkdir($p, 0755, true)) {
        $fitcurePath = $p;
        break;
    }
}

if (!$fitcurePath) {
    die("Cannot find or create fitcure.online directory\n");
}

echo "Target: $fitcurePath\n";

// Step 1: Create directory structure
$dirs = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'tmp', 'vendor'];
foreach ($dirs as $d) {
    $full = "$fitcurePath/$d";
    if (!is_dir($full)) @mkdir($full, 0755, true);
}

// Step 2: Copy essential files from current platform
$files = [
    '.env.example', 'artisan', 'composer.json', 'composer.lock',
];

foreach ($files as $f) {
    $src = "$basePath/platform/$f";
    $dst = "$fitcurePath/$f";
    if (file_exists($src) && !file_exists($dst)) {
        copy($src, $dst);
        echo "  Copied $f\n";
    }
}

// Step 3: Download all platform files from GitHub
$githubFiles = [
    'app/Http/Controllers/Controller.php',
    'app/Http/Kernel.php',
    'app/Models/User.php',
    'app/Models/MemberProfile.php',
    'app/Models/TrainerProfile.php',
    'app/Models/NutritionPlan.php',
    'app/Models/NutritionMeal.php',
    'app/Models/WorkoutPlan.php',
    'app/Models/TimelineEvent.php',
    'app/Models/Appointment.php',
    'app/Models/TrainerWhatsAppConfig.php',
    'app/Services/AiService.php',
    'app/Http/Middleware/CheckRole.php',
    'app/Http/Controllers/AiController.php',
    'app/Http/Controllers/NutritionSaveController.php',
    'app/Http/Controllers/FoodController.php',
    'app/Http/Controllers/Admin/DashboardController.php',
    'app/Http/Controllers/Admin/DoctorController.php',
    'app/Http/Controllers/Admin/TrainerController.php',
    'app/Http/Controllers/Admin/MemberController.php',
    'app/Http/Controllers/Admin/ExerciseController.php',
    'app/Http/Controllers/Trainer/DashboardController.php',
    'app/Http/Controllers/Trainer/TraineeController.php',
    'app/Http/Controllers/Trainer/NutritionPlanController.php',
    'app/Http/Controllers/Trainer/WorkoutPlanController.php',
    'app/Http/Controllers/Trainer/ProgressController.php',
    'app/Http/Controllers/Trainer/WhatsAppController.php',
    'app/Http/Controllers/Doctor/DashboardController.php',
    'app/Http/Controllers/Doctor/AppointmentController.php',
    'app/Http/Controllers/Doctor/PatientController.php',
    'app/Http/Controllers/Doctor/NutritionPlanController.php',
    'app/Http/Controllers/Member/DashboardController.php',
    'app/Http/Controllers/Member/NutritionController.php',
    'app/Http/Controllers/Member/AppointmentController.php',
    'routes/web.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/auth/login.blade.php',
    'resources/views/member/dashboard.blade.php',
    'resources/views/member/nutrition.blade.php',
    'resources/views/member/workouts.blade.php',
    'resources/views/member/progress.blade.php',
    'resources/views/member/appointments.blade.php',
    'resources/views/member/notifications.blade.php',
    'resources/views/member/food-analyzer.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/doctors.blade.php',
    'resources/views/admin/doctor-create.blade.php',
    'resources/views/admin/doctor-edit.blade.php',
    'resources/views/admin/doctor-show.blade.php',
    'resources/views/admin/doctor-patients.blade.php',
    'resources/views/admin/trainers.blade.php',
    'resources/views/admin/trainer-create.blade.php',
    'resources/views/admin/trainer-edit.blade.php',
    'resources/views/admin/trainer-show.blade.php',
    'resources/views/admin/trainer-trainees.blade.php',
    'resources/views/admin/members.blade.php',
    'resources/views/admin/member-create.blade.php',
    'resources/views/admin/member-show.blade.php',
    'resources/views/admin/exercises/index.blade.php',
    'resources/views/admin/exercises/create.blade.php',
    'resources/views/admin/exercises/edit.blade.php',
    'resources/views/trainer/dashboard.blade.php',
    'resources/views/trainer/trainees.blade.php',
    'resources/views/trainer/trainee-create.blade.php',
    'resources/views/trainer/trainee-show.blade.php',
    'resources/views/trainer/nutrition-create.blade.php',
    'resources/views/trainer/nutrition-show.blade.php',
    'resources/views/trainer/workout-create.blade.php',
    'resources/views/trainer/workout-show.blade.php',
    'resources/views/trainer/progress.blade.php',
    'resources/views/trainer/progress-show.blade.php',
    'resources/views/trainer/whatsapp.blade.php',
    'resources/views/doctor/dashboard.blade.php',
    'resources/views/doctor/appointments.blade.php',
    'resources/views/doctor/appointment-create.blade.php',
    'resources/views/doctor/patients.blade.php',
    'resources/views/doctor/patient-show.blade.php',
    'resources/views/doctor/nutrition-create.blade.php',
    'resources/views/foods/analyze.blade.php',
    'config/app.php',
    'config/database.php',
    'config/services.php',
    'public/.htaccess',
    'public/index.php',
    'public/favicon.ico',
];

echo "\nDownloading files from GitHub...\n";
$ok = 0; $fail = 0;
foreach ($githubFiles as $f) {
    $local = "$fitcurePath/$f";
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

echo "\n$ok files downloaded, $fail failed\n";

// Step 4: Create .env
$envContent = "APP_NAME=\"FitCure Online\"
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_URL=https://fitcure.online

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=busnisscard_FitCore
DB_USERNAME=busnisscard_fitcore
DB_PASSWORD=')@h{Mb\$=+C2zc8Bl'

OPENAI_API_KEY=
";

file_put_contents("$fitcurePath/.env", $envContent);
echo "\n.env created\n";

// Step 5: Generate APP_KEY
chdir($fitcurePath);
$output = shell_exec('php artisan key:generate 2>&1');
echo "Key generate: " . ($output ?: "done\n");

// Step 6: Storage link
if (!file_exists("$fitcurePath/public/storage")) {
    $target = "$fitcurePath/storage/app/public";
    $link = "$fitcurePath/public/storage";
    if (is_dir($target)) {
        @symlink($target, $link);
        echo "Storage symlink created\n";
    }
}

// Step 7: Create tmp scripts
$opclear = '<?php require __DIR__ . "/../vendor/autoload.php"; $app = require __DIR__ . "/../bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (function_exists("opcache_reset")) { opcache_reset(); echo "Opcache cleared\n"; } $ai = file_get_contents(__DIR__ . "/../app/Services/AiService.php"); echo str_contains($ai, "openai") ? "AiService: OpenAI OK\n" : "AiService: check\n";';
file_put_contents("$fitcurePath/tmp/opclear.php", $opclear);

echo "\n=== ✅ FitCure Online deployed! ===\n";
echo "URL: https://fitcure.online\n";
echo "Admin: https://fitcure.online/admin/dashboard\n";
