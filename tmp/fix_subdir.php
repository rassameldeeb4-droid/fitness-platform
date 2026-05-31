<?php
// Fix fitcure subdirectory deployment issues
$target = '/home/busnisscard/public_html/fitcure';

echo "=== Fixing fitcure subdirectory ===\n\n";

// 1. Set APP_KEY + APP_DEBUG
$envFile = "$target/.env";
$envContent = file_get_contents($envFile);
$key = 'base64:' . base64_encode(random_bytes(32));
if (preg_match('/APP_KEY=.+/', $envContent)) {
    $envContent = preg_replace('/APP_KEY=.+/', "APP_KEY=$key", $envContent);
} else {
    $envContent = str_replace('APP_KEY=', "APP_KEY=$key", $envContent);
}
// Enable debug
$envContent = str_replace('APP_DEBUG=false', 'APP_DEBUG=true', $envContent);
file_put_contents($envFile, $envContent);
echo "1. APP_KEY set + APP_DEBUG enabled\n";

// 2. Create storage symlink
$storageTarget = "$target/storage/app/public";
$link = "$target/public/storage";
if (!file_exists($link) && is_dir($storageTarget)) {
    @symlink($storageTarget, $link);
    echo "2. Storage symlink created\n";
} elseif (file_exists($link)) {
    echo "2. Storage symlink exists\n";
} else {
    echo "2. Storage target not found, creating...\n";
    @mkdir($storageTarget, 0755, true);
    @symlink($storageTarget, $link);
    echo "   Created target and symlink\n";
}

// 3. Fix .htaccess
$htaccess = "$target/public/.htaccess";
$htContent = file_get_contents($htaccess);
if (!str_contains($htContent, 'RewriteBase')) {
    $htContent = str_replace(
        'RewriteEngine On',
        "RewriteEngine On\nRewriteBase /fitcure/public/",
        $htContent
    );
    file_put_contents($htaccess, $htContent);
    echo "3. .htaccess updated with RewriteBase\n";
} else {
    echo "3. .htaccess already has RewriteBase\n";
}

// 4. Storage permissions
@chmod("$target/storage", 0755);
@chmod("$target/bootstrap/cache", 0755);
echo "4. Permissions set\n";

// 5. Clear view cache
$viewsCache = "$target/storage/framework/views";
if (is_dir($viewsCache)) { array_map('unlink', glob("$viewsCache/*")); }
echo "5. View cache cleared\n";

// 6. Clear opcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "6. Opcache cleared\n";
}

// 7. Show error logs
$logFile = "$target/storage/logs/laravel.log";
if (file_exists($logFile) && filesize($logFile) > 0) {
    $logLines = explode("\n", file_get_contents($logFile));
    $last = array_slice($logLines, -15);
    echo "\n7a. Laravel log (last 15):\n";
    foreach ($last as $l) { if (trim($l)) echo "   " . substr($l, 0, 400) . "\n"; }
} else {
    echo "\n7a. No Laravel log\n";
}

// 7b. Check PHP error_log in docroot
$phpLog = "$target/error_log";
if (file_exists($phpLog) && filesize($phpLog) > 0) {
    $logLines = explode("\n", file_get_contents($phpLog));
    $last = array_slice($logLines, -15);
    echo "\n7b. PHP error_log (last 15):\n";
    foreach ($last as $l) { if (trim($l)) echo "   " . substr($l, 0, 400) . "\n"; }
} else {
    echo "\n7b. No PHP error_log\n";
}

// 7c. Quick PHP syntax check on key files
echo "\n7c. Key file checks:\n";
$keyFiles = [
    "$target/public/index.php",
    "$target/bootstrap/app.php",
    "$target/vendor/autoload.php",
    "$target/artisan",
];
foreach ($keyFiles as $kf) {
    $exists = file_exists($kf);
    $size = $exists ? filesize($kf) : 0;
    echo "   " . basename(dirname(dirname($kf))) . "/" . basename($kf) . ": " . ($exists ? "OK ($size B)" : "MISSING") . "\n";
}

// 7d. Test artisan
chdir($target);
echo "\n7d. Artisan test:\n";
$artOut = shell_exec('php artisan --version 2>&1');
echo "   " . ($artOut ?: "failed") . "\n";
$envOut = shell_exec('php artisan env 2>&1');
echo "   " . ($envOut ?: "failed") . "\n";

// 7e. Check APP_URL
$env = file_get_contents("$target/.env");
preg_match('/APP_URL=(.+)/', $env, $m);
echo "   APP_URL: " . ($m[1] ?? 'NOT SET') . "\n";

echo "\n=== Done ===\n";
echo "Try: https://busnisscard.com/fitcure/public/\n";
