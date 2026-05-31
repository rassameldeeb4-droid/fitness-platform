<?php
// Diagnose Laravel 500 error
$target = '/home/busnisscard/public_html/fitcure';

echo "=== Diagnosing fitcure Laravel 500 error ===\n\n";

// 1. Check .env
$env = file_get_contents("$target/.env");
echo "1. .env APP_KEY: ";
if (preg_match('/APP_KEY=(.+)/', $env, $m)) {
    echo strlen(trim($m[1])) . " chars\n";
    if (!str_contains($m[1], 'base64:')) echo "   WARNING: Key missing base64: prefix!\n";
} else echo "MISSING!\n";

// 2. Check .htaccess
$ht = file_get_contents("$target/public/.htaccess");
echo "2. .htaccess RewriteBase: " . (str_contains($ht, 'RewriteBase') ? "present\n" : "MISSING\n");
echo "   RewriteRule ^(.*)$ index.php: " . (str_contains($ht, 'index.php') ? "present\n" : "MISSING\n");

// 3. Check vendor autoload
echo "3. vendor/autoload.php: " . (file_exists("$target/vendor/autoload.php") ? "OK\n" : "MISSING\n");

// 4. Check bootstrap app
echo "4. bootstrap/app.php: " . (file_exists("$target/bootstrap/app.php") ? "OK\n" : "MISSING\n");

// 5. Check bootstrap cache
$cacheDir = "$target/bootstrap/cache";
echo "5. bootstrap/cache: ";
if (is_dir($cacheDir)) {
    $files = scandir($cacheDir);
    echo "exists (" . (count($files) - 2) . " files)\n";
} else "MISSING\n";

// 6. Check storage
echo "6. storage: ";
$storageDirs = ['app/public', 'framework/cache', 'framework/sessions', 'framework/views', 'logs'];
foreach ($storageDirs as $d) {
    $p = "$target/storage/$d";
    echo (is_dir($p) ? "[$d: OK] " : "[$d: MISSING] ");
}
echo "\n";

// 7. Check storage symlink
$link = "$target/public/storage";
echo "7. public/storage: " . (file_exists($link) ? "exists" : "MISSING") . "\n";
if (file_exists($link)) echo "   Target: " . readlink($link) . "\n";

// 8. Try to read Laravel error log
$logFile = "$target/storage/logs/laravel.log";
if (file_exists($logFile)) {
    $log = file_get_contents($logFile);
    // Get last 10 lines
    $lines = array_slice(explode("\n", $log), -10);
    echo "\n8. Last 10 log lines:\n";
    foreach ($lines as $l) { if (trim($l)) echo "   $l\n"; }
} else {
    echo "8. No laravel.log found (or empty)\n";
}

// 9. Check PHP version
echo "\n9. PHP: " . phpversion() . "\n";

// 10. Check storage is writable
$testFile = "$target/storage/logs/_test_write.txt";
@file_put_contents($testFile, 'test');
echo "10. Storage writable: " . (file_exists($testFile) ? "OK" : "NO") . "\n";
@unlink($testFile);

// 11. Check APP_DEBUG
echo "11. APP_DEBUG: " . (str_contains($env, 'APP_DEBUG=true') ? "true" : "false") . "\n";

// 12. Check if public/index.php works standalone
echo "\n12. public/index.php header test:\n";
$indexContent = file_get_contents("$target/public/index.php");
if (str_contains($indexContent, '<?php')) echo "   Has PHP header\n";
if (str_contains($indexContent, 'Autoload')) echo "   Has Autoload reference\n";
if (str_contains($indexContent, 'bootstrap')) echo "   Has bootstrap reference\n";

echo "\n=== Done ===\n";
