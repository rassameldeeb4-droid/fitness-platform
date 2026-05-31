<?php
// Deploy FitCore to busnisscard.com/fitcure/ subdirectory
echo "=== Deploying to /fitcure/ subdirectory ===\n\n";

$base = '/home/busnisscard/public_html';
$target = "$base/fitcure";
$appUrl = 'https://busnisscard.com/fitcure/public';

// Step 1: Download all platform files
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
];

echo "Downloading files from GitHub...\n";
$ok = 0; $fail = 0;
foreach ($githubFiles as $f) {
    $local = "$target/$f";
    $dir = dirname($local);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $content = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/' . $f);
    if ($content) {
        file_put_contents($local, $content);
        echo "  ? $f\n"; $ok++;
    } else {
        echo "  ? $f\n"; $fail++;
    }
}
echo "\n$ok files downloaded, $fail failed\n";

// Step 2: Copy vendor from platform
echo "\nCopying vendor...\n";
$srcVendor = "$base/platform/vendor";
$dstVendor = "$target/vendor";
$vendorOk = 0;
if (is_dir($srcVendor)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcVendor, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $f) {
        $dest = $dstVendor . '/' . $it->getSubPathname();
        if ($f->isDir()) {
            @mkdir($dest, 0755, true);
        } else {
            @copy($f, $dest);
            $vendorOk++;
        }
    }
    echo "Vendor: copied $vendorOk files\n";
} else {
    echo "Vendor source not found!\n";
}

// Step 3: Create .env
$envContent = "APP_NAME=\"FitCure\"
APP_ENV=local
APP_DEBUG=false
APP_KEY=
APP_URL=$appUrl

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=busnisscard_FitCore
DB_USERNAME=busnisscard_fitcore
DB_PASSWORD=')@h{Mb\$=+C2zc8Bl'

OPENAI_API_KEY=
";

file_put_contents("$target/.env", $envContent);
echo ".env created\n";

// Step 4: Generate APP_KEY
chdir($target);
$output = shell_exec('php artisan key:generate 2>&1');
echo "Key generate: " . ($output ?: "done\n");

// Step 5: Create storage structure
echo "\nStorage setup...\n";
foreach (['app/public', 'framework/cache', 'framework/sessions', 'framework/views', 'logs'] as $d) {
    @mkdir("$target/storage/$d", 0755, true);
}

// Step 6: Storage link
if (!file_exists("$target/public/storage")) {
    @symlink("$target/storage/app/public", "$target/public/storage");
}

// Step 7: Create bootstrap cache
@mkdir("$target/bootstrap/cache", 0755, true);

// Step 8: Fix .htaccess for subdirectory
$htaccess = "$target/public/.htaccess";
$c = file_get_contents($htaccess);
if ($c && strpos($c, 'RewriteBase') === false) {
    $c = str_replace(
        '<IfModule mod_rewrite.c>',
        "<IfModule mod_rewrite.c>\n    RewriteBase /fitcure/public/",
        $c
    );
    file_put_contents($htaccess, $c);
    echo ".htaccess updated with RewriteBase\n";
}

// Step 9: Set directory permissions
@chmod("$target/storage", 0755);
@chmod("$target/bootstrap/cache", 0755);

// Step 10: Create tmp scripts
$opclear = '<?php require __DIR__ . "/../vendor/autoload.php"; $app = require __DIR__ . "/../bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (function_exists("opcache_reset")) { opcache_reset(); echo "Opcache cleared\n"; }';
file_put_contents("$target/tmp/opclear.php", $opclear);

// Step 11: Clear view cache
$viewsCache = "$target/storage/framework/views";
if (is_dir($viewsCache)) { array_map('unlink', glob("$viewsCache/*")); }

echo "\n=== FitCure deployed to subdirectory ===\n";
echo "URL: $appUrl\n";
echo "Login: same users/passwords as main platform\n";
echo "\nImportant: Run this script again after deploying?\n";
