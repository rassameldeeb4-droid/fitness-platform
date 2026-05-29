<?php
if (function_exists('opcache_reset')) opcache_reset();
if (function_exists('apc_clear_cache')) apc_clear_cache();
$f = __DIR__ . '/../app/Services/AiService.php';
$c = file_get_contents($f);
if (str_contains($c, 'OpenAI')) echo "AiService: OpenAI OK\n";
else echo "AiService: still Gemini!\n";
