<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<pre>";

echo "Running key:generate...\n";
Artisan::call('key:generate', ['--force' => true]);
echo Artisan::output() . "\n";

echo "Running storage:link...\n";
Artisan::call('storage:link', ['--force' => true]);
echo Artisan::output() . "\n";

echo "Running migrate...\n";
Artisan::call('migrate', ['--force' => true, '--seed' => false]);
echo Artisan::output() . "\n";

echo "Done! You can delete this file now.</pre>";
