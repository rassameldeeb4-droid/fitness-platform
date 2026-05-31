<?php
// Find the actual document root for fitcure.online

$candidates = [
    '/home/busnisscard/public_html/fitcure.online',
    '/home/busnisscard/fitcure.online',
    '/home/busnisscard/public_html/fitcure.online/public_html',
    '/home/busnisscard/public_html/fitcure.online/public',
    '/home/busnisscard/fitcure.online/public_html',
    '/home/busnisscard/fitcure.online/public',
    '/home/busnisscard/public_html',
    '/home/busnisscard/public_html/fitcure',
    '/home/busnisscard/fitcure',
];

echo "Current script: " . __FILE__ . "\n";
echo "Current dir: " . __DIR__ . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n\n";

echo "Checking candidates for cgi-bin:\n";
foreach ($candidates as $p) {
    $cgi = "$p/cgi-bin";
    if (is_dir($cgi)) {
        echo "  ✅ $p (has cgi-bin)\n";
    } else {
        echo "  ❌ $p\n";
    }
}

echo "\nChecking if our deployed files exist:\n";
$paths = [
    '/home/busnisscard/public_html/fitcure.online/.env',
    '/home/busnisscard/fitcure.online/.env',
    '/home/busnisscard/public_html/fitcure.online/public/index.php',
    '/home/busnisscard/fitcure.online/public/index.php',
];
foreach ($paths as $p) {
    echo "  " . (file_exists($p) ? "✅" : "❌") . " $p\n";
}
