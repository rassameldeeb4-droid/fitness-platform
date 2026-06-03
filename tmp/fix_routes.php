<?php
$file = __DIR__ . '/../routes/web.php';
$content = file_get_contents($file);
$lines = explode("\n", $content);

// Find duplicate lines
$seen = [];
$newLines = [];
$changed = false;
foreach ($lines as $line) {
    $trimmed = trim($line);
    if (str_starts_with($trimmed, 'use ') && str_ends_with($trimmed, ';')) {
        if (in_array($trimmed, $seen)) {
            echo "Removing duplicate: $trimmed\n";
            $changed = true;
            continue;
        }
        $seen[] = $trimmed;
    }
    $newLines[] = $line;
}

file_put_contents($file, implode("\n", $newLines));
if ($changed) {
    echo "Done. Fixed $file, Lines: " . count($newLines) . "\n";
} else {
    echo "No duplicates found in $file\n";
}

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "Opcache reset\n";
}
echo "Site should work now.\n";
