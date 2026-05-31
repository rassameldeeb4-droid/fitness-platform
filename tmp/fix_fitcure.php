<?php
// Fix and complete fitcure.online deployment

$fitcurePath = '/home/busnisscard/public_html/fitcure.online';
$sourcePath = '/home/busnisscard/public_html/platform';

if (!is_dir($fitcurePath)) {
    die("$fitcurePath not found\n");
}

echo "Fixing fitcure.online at $fitcurePath\n\n";

// 1. Copy vendor from busnisscard platform
echo "1. Copying vendor... ";
$vendorSrc = "$sourcePath/vendor";
$vendorDst = "$fitcurePath/vendor";
$vendorOk = 0;
if (is_dir($vendorSrc)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($vendorSrc, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $f) {
        $dest = $vendorDst . '/' . $it->getSubPathname();
        if ($f->isDir()) {
            @mkdir($dest, 0755, true);
        } else {
            @copy($f, $dest);
            $vendorOk++;
        }
    }
    echo "copied $vendorOk files\n";
} else {
    echo "source vendor not found!\n";
}

// 2. Copy storage structure
echo "2. Copying storage... ";
$storageSrc = "$sourcePath/storage";
$storageDst = "$fitcurePath/storage";
$dirs = ['app/public', 'framework/cache', 'framework/sessions', 'framework/views', 'logs'];
foreach ($dirs as $d) {
    @mkdir("$storageDst/$d", 0755, true);
}
// Copy .gitignore files
foreach (['app/public/.gitignore', 'framework/cache/.gitignore', 'framework/sessions/.gitignore', 'framework/views/.gitignore', 'logs/.gitignore'] as $f) {
    $src = "$storageSrc/$f";
    $dst = "$storageDst/$f";
    if (file_exists($src)) @copy($src, $dst);
}
echo "done\n";

// 3. Copy bootstrap/cache
echo "3. Copying bootstrap cache... ";
$bootstrapSrc = "$sourcePath/bootstrap";
$bootstrapDst = "$fitcurePath/bootstrap";
foreach (['app.php', 'cache/.gitignore', 'cache/services.php', 'cache/packages.php'] as $f) {
    $src = "$bootstrapSrc/$f";
    $dst = "$bootstrapDst/$f";
    if (file_exists($src)) { @mkdir(dirname($dst), 0755, true); @copy($src, $dst); }
}
echo "done\n";

// 4. Generate APP_KEY properly
echo "4. Generating APP_KEY... ";
$envFile = "$fitcurePath/.env";
$envContent = file_get_contents($envFile);
// Generate a random base64 key
$key = 'base64:' . base64_encode(random_bytes(32));
if (preg_match('/APP_KEY=.+/', $envContent)) {
    $envContent = preg_replace('/APP_KEY=.+/', "APP_KEY=$key", $envContent);
} else {
    $envContent = str_replace('APP_KEY=', "APP_KEY=$key", $envContent);
}
file_put_contents($envFile, $envContent);
echo "$key\n";

// 5. Create storage symlink
echo "5. Storage symlink... ";
$target = "$fitcurePath/storage/app/public";
$link = "$fitcurePath/public/storage";
if (!file_exists($link) && is_dir($target)) {
    @symlink($target, $link);
    echo "created\n";
} elseif (file_exists($link)) {
    echo "exists\n";
} else {
    echo "failed (target not found)\n";
}

// 6. Fix .htaccess
echo "6. .htaccess... ";
$htaccess = "$fitcurePath/public/.htaccess";
$htContent = file_get_contents($htaccess);
if (!str_contains($htContent, 'RewriteBase')) {
    // Add RewriteBase for subdirectory
    $htContent = str_replace(
        'RewriteEngine On',
        "RewriteEngine On\nRewriteBase /",
        $htContent
    );
    file_put_contents($htaccess, $htContent);
    echo "updated\n";
} else {
    echo "ok\n";
}

// 7. Download failed files (3)
echo "\n7. Retrying failed downloads...\n";
$failedFiles = [
    'resources/views/member/workouts.blade.php',
    'resources/views/member/progress.blade.php',
    'resources/views/member/notifications.blade.php',
];
// Actually check what exists vs missing
$allFiles = [
    'resources/views/member/workouts.blade.php',
    'resources/views/member/progress.blade.php', 
    'resources/views/member/notifications.blade.php',
    'resources/views/member/appointments.blade.php',
    'resources/views/admin/exercises/index.blade.php',
    'resources/views/admin/exercises/create.blade.php',
    'resources/views/admin/exercises/edit.blade.php',
    'resources/views/trainer/nutrition-show.blade.php',
    'resources/views/trainer/workout-show.blade.php',
    'resources/views/trainer/progress-show.blade.php',
    'resources/views/doctor/nutrition-create.blade.php',
    'resources/views/foods/analyze.blade.php',
    'public/favicon.ico',
];
$ok = 0; $fail = 0;
foreach ($allFiles as $f) {
    $local = "$fitcurePath/$f";
    if (file_exists($local) && filesize($local) > 0) { $ok++; continue; }
    $dir = dirname($local);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $content = @file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/' . $f);
    if ($content) {
        file_put_contents($local, $content);
        echo "  ✅ $f\n"; $ok++;
    } else {
        echo "  ❌ $f\n"; $fail++;
    }
}
echo "$ok files OK, $fail failed\n";

echo "\n=== ✅ FitCure fix complete! ===\n";
echo "Try: https://fitcure.online/public/\n";
