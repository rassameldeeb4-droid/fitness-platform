<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');
$path = __DIR__ . '/../routes/web.php';
echo "File exists: " . (file_exists($path) ? 'yes' : 'no') . "\n";
echo "File size: " . filesize($path) . "\n\n";

// Show the admin group section
$lines = file($path);
$inAdmin = false; $adminLines = [];
foreach ($lines as $i => $line) {
    if (strpos($line, "prefix('admin')") !== false) $inAdmin = true;
    if ($inAdmin) {
        $adminLines[] = ($i+1) . ': ' . $line;
        if (trim($line) === '});' && substr_count(implode('', array_slice($lines, 0, $i+1)), '});') >= 7) {
            break;
        }
    }
}
echo "=== Admin group routes ===\n";
echo implode('', $adminLines);

echo "\n\n=== Parse check ===\n";
$result = @php -l $path;
echo $result;
