<?php
@set_time_limit(0);
echo "<pre>=== Deploy Settings Only ===\n";
$files = [
    'app/Models/AppSetting.php',
    'app/Http/Controllers/Admin/SettingsController.php',
    'resources/views/admin/settings.blade.php',
    'database/migrations/2026_06_03_000004_create_app_settings_table.php',
];
$ok = 0;
foreach ($files as $f) {
    $local = __DIR__ . '/../' . $f;
    $dir = dirname($local);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $content = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/' . $f);
    if ($content) {
        file_put_contents($local, $content);
        echo "  ✅ $f\n"; $ok++;
    } else {
        echo "  ❌ $f\n";
    }
}
echo "\n$ok files downloaded\n";

// Create app_settings table
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('app_settings')) {
    Schema::create('app_settings', function ($t) {
        $t->string('key')->primary();
        $t->text('value')->nullable();
        $t->timestamps();
    });
    \App\Models\AppSetting::setValue('site_name', 'Fitness Platform');
    \App\Models\AppSetting::setValue('site_email', 'admin@fitness.com');
    \App\Models\AppSetting::setValue('notif_expiry_7', '1');
    \App\Models\AppSetting::setValue('notif_auto_renew', '1');
    \App\Models\AppSetting::setValue('notif_late_payment', '0');
    echo "app_settings table created + defaults set\n";
} else {
    echo "app_settings table already exists\n";
}

if (function_exists('opcache_reset')) opcache_reset();
echo "Opcache cleared\nDone.</pre>";
