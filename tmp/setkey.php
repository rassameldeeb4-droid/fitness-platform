<?php
// Set GEMINI_API_KEY in .env
$key = base64_decode('QVEuQWI4Uk42THlXRTFUSFZJd3BWdXJUVmNseXcwWTNQYWROVjFxdW5waU1jYWI5OWNJbXc=');
$envFile = __DIR__ . '/../.env';
$content = file_exists($envFile) ? file_get_contents($envFile) : '';
if (preg_match('/^GEMINI_API_KEY=.*$/m', $content)) {
    $content = preg_replace('/^GEMINI_API_KEY=.*$/m', "GEMINI_API_KEY=$key", $content);
    file_put_contents($envFile, $content);
    echo "GEMINI_API_KEY updated\n";
} elseif (!str_contains($content, 'GEMINI_API_KEY=')) {
    file_put_contents($envFile, $content . "\nGEMINI_API_KEY=$key\n");
    echo "GEMINI_API_KEY added\n";
} else echo "GEMINI_API_KEY already set\n";
