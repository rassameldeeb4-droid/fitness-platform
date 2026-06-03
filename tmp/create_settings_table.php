<?php
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
    echo "app_settings table created\n";
    // Set defaults
    \App\Models\AppSetting::setValue('site_name', 'Fitness Platform');
    \App\Models\AppSetting::setValue('site_email', 'admin@fitness.com');
    \App\Models\AppSetting::setValue('notif_expiry_7', '1');
    \App\Models\AppSetting::setValue('notif_auto_renew', '1');
    \App\Models\AppSetting::setValue('notif_late_payment', '0');
    echo "Defaults set\n";
} else {
    echo "app_settings table already exists\n";
}
