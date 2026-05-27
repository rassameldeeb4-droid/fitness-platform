<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "<pre>";

echo "Running key:generate...\n";
Artisan::call('key:generate', ['--force' => true]);
echo Artisan::output() . "\n";

echo "Running storage:link...\n";
Artisan::call('storage:link', ['--force' => true]);
echo Artisan::output() . "\n";

echo "Dropping all tables...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');
$tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [DB::getDatabaseName()]);
foreach ($tables as $table) {
    $name = $table->TABLE_NAME;
    DB::statement("DROP TABLE IF EXISTS `$name`");
}
DB::statement('SET FOREIGN_KEY_CHECKS=1');
echo "All tables dropped.\n";

echo "Running migrate...\n";
Artisan::call('migrate', ['--force' => true, '--seed' => false]);
echo Artisan::output() . "\n";

echo "Done! You can delete this file now.</pre>";
