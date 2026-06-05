<?php
$f = __DIR__ . '/../resources/views/layouts/app.blade.php';
$c = file_get_contents($f);
$count = substr_count($c, 'admin.doctors');
echo "admin.doctors count: $count\n";
// Show lines with admin.doctors
$lines = explode("\n", $c);
foreach ($lines as $i => $line) {
    if (str_contains($line, 'admin.doctors')) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
