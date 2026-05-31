<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain; charset=utf-8');

echo "=== fitcure debug ===\n\n";

$fcRoot = '/home/busnisscard/public_html/fitcure';
echo "Root: $fcRoot\n\n";

// Check bootstrap cache files
echo "bootstrap/cache:\n";
$cacheDir = "$fcRoot/bootstrap/cache";
$files = glob("$cacheDir/*.php");
if ($files) {
    foreach ($files as $f) {
        echo "  " . basename($f) . " (" . filesize($f) . " bytes)\n";
    }
} else {
    echo "  (no files)\n";
}

echo "\n";

// Step-by-step boot
chdir($fcRoot);
try {
    echo "1. Autoload... ";
    $r = require "$fcRoot/vendor/autoload.php";
    echo "OK\n";
    
    echo "2. App... ";
    $app = require_once "$fcRoot/bootstrap/app.php";
    echo "OK\n";
    
    echo "3. Kernel... ";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "OK\n";
    
    echo "4. Request... ";
    $request = Illuminate\Http\Request::capture();
    echo "OK\n";
    
    echo "5. Handle... ";
    $response = $kernel->handle($request);
    echo "OK (" . get_class($response) . ")\n";
    
    echo "6. Status: " . $response->getStatusCode() . "\n";
    echo "7. Full response:\n" . $response->getContent() . "\n";
    
    echo "\n=== BOOT OK ===\n";
} catch (\Throwable $e) {
    echo "\n=== FAILED ===\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}