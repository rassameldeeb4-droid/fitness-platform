<?php
echo "=== fitcure.online document root probe ===\n\n";

// Method 1: Check via PHP server vars
echo "Method 1: PHP server vars\n";
echo "  DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "  SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n";
echo "  __FILE__: " . __FILE__ . "\n\n";

// Method 2: Check Apache config files for fitcure.online vhost
echo "Method 2: Apache vhost configs\n";
$vhostFiles = array_merge(
    glob('/etc/apache2/sites-enabled/*.conf'),
    glob('/etc/apache2/sites-available/*.conf'),
    glob('/etc/httpd/sites-enabled/*.conf'),
    glob('/etc/httpd/sites-available/*.conf'),
);
foreach ($vhostFiles as $vf) {
    $content = @file_get_contents($vf);
    if (stripos($content, 'fitcure') !== false) {
        echo "  Found in: $vf\n";
        if (preg_match('/DocumentRoot\s+(\S+)/i', $content, $m)) {
            echo "  DocumentRoot: {$m[1]}\n";
        }
        break;
    }
}

// Method 3: Check cPanel Apache includes
echo "Method 3: cPanel Apache includes\n";
$includeDirs = [
    '/etc/apache2/conf.d/userdata/ssl/2_4/busnisscard',
    '/etc/apache2/conf.d/userdata/std/2_4/busnisscard',
];
foreach ($includeDirs as $dir) {
    if (!is_dir($dir)) {
        echo "  $dir: NOT FOUND\n";
        continue;
    }
    $files = scandir($dir);
    foreach ($files as $f) {
        if (str_ends_with($f, '.conf') || str_ends_with($f, '.conf.stock')) {
            $fc = @file_get_contents("$dir/$f");
            if (stripos($fc, 'fitcure') !== false) {
                echo "  Found in: $dir/$f\n";
                if (preg_match('/DocumentRoot\s+(\S+)/i', $fc, $m)) {
                    echo "  DocumentRoot: {$m[1]}\n";
                }
            }
        }
    }
}

// Method 4: Search all .conf files recursively
echo "Method 4: Recursive search for fitcure in .conf files\n";
$output = shell_exec('grep -rl fitcure /etc/apache2/ /etc/httpd/ 2>/dev/null | head -10');
if ($output) {
    echo "  Found in:\n$output";
} else {
    echo "  No results from grep\n";
}

// Method 5: Check custom vhost files (EasyApache)
echo "Method 5: Checking EasyApache vhost includes\n";
$eaDirs = [
    '/etc/apache2/conf.d/userdata',
    '/usr/local/apache/conf/userdata',
    '/etc/apache2/sites-enabled',
    '/etc/httpd/conf.d',
    '/opt/cpanel/ea-apache24/conf',
];
foreach ($eaDirs as $d) {
    if (!is_dir($d)) continue;
    $out = shell_exec("grep -rl fitcure '$d' 2>/dev/null | head -10");
    if ($out) echo "  Found in $d:\n$out\n";
}

// Method 6: Find the Apache binary and check compiled config
echo "Method 6: Apache config dump\n";
$httpd = shell_exec('which httpd 2>/dev/null || which apache2 2>/dev/null');
echo "  Apache binary: " . ($httpd ? trim($httpd) : 'not found') . "\n";

echo "\n=== Done ===\n";
