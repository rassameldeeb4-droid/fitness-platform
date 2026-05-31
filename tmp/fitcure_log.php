<?php
header('Content-Type: text/plain; charset=utf-8');

$paths = [
    '/home/busnisscard/public_html/fitcure/storage/logs/laravel.log',
    '/home/busnisscard/public_html/fitcure/error_log',
];

foreach ($paths as $p) {
    echo "=== " . basename(dirname(dirname($p))) . "/" . basename($p) . " ===\n";
    if (file_exists($p) && filesize($p) > 0) {
        echo file_get_contents($p) . "\n\n";
    } else {
        echo "(empty or not found)\n\n";
    }
}

// Also check bootstrap cache
$cacheDir = '/home/busnisscard/public_html/fitcure/bootstrap/cache';
echo "=== bootstrap/cache/ ===\n";
$files = glob("$cacheDir/*.php");
if ($files) {
    foreach ($files as $f) {
        echo "  " . basename($f) . " (" . filesize($f) . " bytes)\n";
    }
} else {
    echo "  (no files)\n";
}