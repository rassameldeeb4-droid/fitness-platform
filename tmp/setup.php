<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Schema::defaultStringLength(191);

echo "<pre>";

echo "Dropping all tables...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');
$tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [DB::getDatabaseName()]);
foreach ($tables as $table) {
    $name = $table->TABLE_NAME;
    DB::statement("DROP TABLE IF EXISTS `$name`");
}
DB::statement('SET FOREIGN_KEY_CHECKS=1');
echo "All tables dropped.\n";

echo "Running migrations directly...\n";
$migrations = glob(__DIR__ . '/../database/migrations/*.php');
sort($migrations);
foreach ($migrations as $file) {
    $name = basename($file);
    echo "  Running: $name\n";
    try {
        $migration = require $file;
        $migration->up();
        echo "    OK\n";
    } catch (\Exception $e) {
        echo "    ERROR: " . $e->getMessage() . "\n";
        echo "    Skipping...\n";
    }
}
echo "Done!</pre>";
