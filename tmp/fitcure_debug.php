<?php
// Fitcure error log reader - self-updates via GitHub
$version = '1.0';
$localHash = @md5_file(__FILE__);
$remoteHash = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/tmp/fitcure_debug.php.hash');
if ($remoteHash && trim($remoteHash) !== $localHash) {
    $newCode = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/tmp/fitcure_debug.php');
    if ($newCode) {
        eval($newCode);
        exit;
    }
}

header('Content-Type: text/plain; charset=utf-8');
echo "=== Fitcure Debug v$version ===\n\n";

$fitcureRoot = '/home/busnisscard/public_html/fitcure';

// 1. PHP error log
$phpLog = "$fitcureRoot/error_log";
if (file_exists($phpLog) && filesize($phpLog) > 0) {
    echo "--- PHP error_log ---\n";
    $lines = file($phpLog);
    foreach (array_slice($lines, -20) as $l) {
        $l = trim($l);
        if ($l) echo wordwrap($l, 200) . "\n\n";
    }
    file_put_contents($phpLog, '');
} else {
    echo "PHP error_log: empty/not found\n";
}

// 2. Laravel log
$laravelLog = "$fitcureRoot/storage/logs/laravel.log";
if (file_exists($laravelLog) && filesize($laravelLog) > 0) {
    echo "\n--- Laravel log ---\n";
    $lines = file($laravelLog);
    foreach (array_slice($lines, -10) as $l) {
        $l = trim($l);
        if ($l) echo wordwrap(substr($l, 0, 500), 200) . "\n\n";
    }
    file_put_contents($laravelLog, '');
} else {
    echo "Laravel log: empty/not found\n";
}

// 3. .env key settings
$envContent = @file_get_contents("$fitcureRoot/.env");
if ($envContent) {
    echo "--- .env key values ---\n";
    foreach (['APP_KEY', 'APP_URL', 'APP_DEBUG', 'APP_ENV', 'DB_DATABASE', 'DB_HOST', 'DB_USERNAME', 'SESSION_DRIVER', 'SESSION_DOMAIN'] as $k) {
        if (preg_match("/^$k=(.*)$/m", $envContent, $m)) echo "  $k = " . $m[1] . "\n";
    }
}

// 4. HTTPS test
echo "\n--- Direct HTTP test ---\n";
$ch = curl_init('https://busnisscard.com/fitcure/public/');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
    CURLOPT_HEADER => true, CURLOPT_NOBODY => false,
]);
$body = curl_exec($ch);
$info = curl_getinfo($ch);
$err = curl_error($ch);
curl_close($ch);
echo "  HTTP " . $info['http_code'] . "\n";
echo "  Content: " . substr($body, 0, 300) . "\n";
if ($err) echo "  curl error: $err\n";

echo "\n=== Done ===\n";