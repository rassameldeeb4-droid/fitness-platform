<?php
if (function_exists('opcache_reset')) opcache_reset();
if (function_exists('apc_clear_cache')) apc_clear_cache();
$f = __DIR__ . '/../app/Services/AiService.php';
$c = file_get_contents($f);
if (str_contains($c, 'OpenAI')) echo "AiService: OpenAI OK\n";
else echo "AiService: still Gemini!\n";

// Check fix_subdir.php content
$sf = __DIR__ . '/fix_subdir.php';
if (file_exists($sf)) {
    $c2 = file_get_contents($sf);
    echo "fix_subdir.php: " . strlen($c2) . " bytes, has 'error_log' text: " . (str_contains($c2, 'error_log') ? "YES" : "NO") . ", mtime: " . date('Y-m-d H:i:s', filemtime($sf)) . "\n";
} else {
    echo "fix_subdir.php: NOT FOUND\n";
}

// Opcache status
echo "Opcache: " . (function_exists('opcache_get_status') ? 'enabled' : 'NOT enabled') . "\n";
if (function_exists('opcache_get_status')) {
    $st = opcache_get_status(false);
    echo "  Active: " . ($st['opcache_enabled'] ? 'YES' : 'NO') . "\n";
    echo "  Cached files: " . ($st['num_cached_scripts'] ?? 'N/A') . "\n";
    echo "  Hits: " . ($st['hits'] ?? 'N/A') . ", Misses: " . ($st['misses'] ?? 'N/A') . "\n";
    // Check if fix_subdir.php is in opcache
    foreach (($st['scripts'] ?? []) as $sc => $info) {
        if (strpos($sc, 'fix_subdir') !== false) {
            echo "  fix_subdir.php cached at: " . date('Y-m-d H:i:s', $info['timestamp']) . "\n";
        }
    }
}
