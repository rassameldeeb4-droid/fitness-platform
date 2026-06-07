<?php
// 1. Delete Blade compiled views so they get recompiled
$viewsDir = __DIR__ . '/../storage/framework/views';
if (is_dir($viewsDir)) {
    foreach (glob($viewsDir . '/*') ?: [] as $f) {
        if (basename($f) !== '.gitignore') @unlink($f);
    }
    echo "Blade views cleared: YES\n";
} else {
    echo "Blade views dir not found\n";
}

// 2. Try to find and clear opcache file cache
$possibleDirs = [
    '/tmp/opcache',
    '/tmp/systemd-private-*/php*/tmp/opcache',
    '/var/tmp/opcache',
    sys_get_temp_dir() . '/opcache',
];
foreach ($possibleDirs as $pattern) {
    foreach (glob($pattern) ?: [] as $dir) {
        if (is_dir($dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            $count = 0;
            foreach ($files as $f) {
                if ($f->isFile()) @unlink($f->getRealPath());
                if ($f->isDir()) @rmdir($f->getRealPath());
                $count++;
            }
            echo "OpCache file cache cleared: $dir ($count files)\n";
        }
    }
}

// 3. opcache_reset (main memory)
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "opcache_reset(): YES\n";
} else {
    echo "opcache_reset(): NOT available\n";
}

if (function_exists('apc_clear_cache')) apc_clear_cache();

// 4. Verify the controller
$ctrl = __DIR__ . '/../app/Http/Controllers/Doctor/DashboardController.php';
$c = file_get_contents($ctrl);
if (str_contains($c, 'upcomingAppointments')) {
    echo "Controller: HAS \$upcomingAppointments - OK\n";
} else {
    echo "Controller: MISSING \$upcomingAppointments - PROBLEM\n";
}

// 5. Verify view
$view = __DIR__ . '/../resources/views/doctor/dashboard.blade.php';
$v = file_get_contents($view);
if (str_contains($v, 'upcomingAppointments')) {
    echo "View: HAS \$upcomingAppointments - OK\n";
} else {
    echo "View: MISSING \$upcomingAppointments - PROBLEM\n";
}

// Opcache status
echo "Opcache: " . (function_exists('opcache_get_status') ? 'enabled' : 'NOT enabled') . "\n";
if (function_exists('opcache_get_status')) {
    $st = opcache_get_status(false);
    echo "  Active: " . ($st['opcache_enabled'] ? 'YES' : 'NO') . "\n";
    echo "  Cached files: " . ($st['num_cached_scripts'] ?? 'N/A') . "\n";
    echo "  Hits: " . ($st['hits'] ?? 'N/A') . ", Misses: " . ($st['misses'] ?? 'N/A') . "\n";
}

echo "\nDone. Try accessing the doctor dashboard now.";
