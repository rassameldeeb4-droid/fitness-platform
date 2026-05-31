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
    echo "fix_subdir.php: " . strlen($c2) . " bytes, has Laravel log: " . (str_contains($c2, 'Laravel log') ? "YES" : "NO") . "\n";
} else {
    echo "fix_subdir.php: NOT FOUND\n";
}
