<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "<pre>";

// Create .htaccess
$htaccess = __DIR__ . '/../public/.htaccess';
echo "Creating .htaccess...\n";
file_put_contents($htaccess, "RewriteEngine On\nRewriteBase /platform/public/\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteRule ^ index.php [L]\n");
echo "  Done\n";

// Fix Vite in layouts
$guest = __DIR__ . '/../resources/views/layouts/guest.blade.php';
$content = file_get_contents($guest);
$content = str_replace('@vite([\'resources/css/app.css\', \'resources/js/app.js\'])', '{{-- Vite disabled --}}', $content);
file_put_contents($guest, $content);
echo "  Vite removed from guest layout\n";

$appLayout = __DIR__ . '/../resources/views/layouts/app.blade.php';
$content = file_get_contents($appLayout);
$content = str_replace('@vite([\'resources/css/app.css\'])', '{{-- Vite disabled --}}', $content);
file_put_contents($appLayout, $content);
echo "  Vite removed from app layout\n";

// Clear view cache
$viewsDir = __DIR__ . '/../storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*');
    if ($files) array_map('unlink', $files);
}
echo "  View cache cleared\n";

// Create admin user
echo "Checking for existing admin user...\n";
$admin = DB::table('users')->where('email', 'admin@test.com')->first();
if (!$admin) {
    DB::table('users')->insert([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "  Admin user created: admin@test.com / password\n";
} else {
    echo "  Admin user already exists\n";
}

echo "\nAll fixes applied!</pre>";
