<?php
// v2 - deploy fitcure platform using a different approach
// First, test if we can create files and access them via HTTP

function testPath($basePath) {
    $testFile = "$basePath/__fitcure_test.txt";
    @mkdir($basePath, 0755, true);
    $written = @file_put_contents($testFile, 'test_' . time());
    $exists = file_exists($testFile);
    $readable = is_readable($testFile);
    return [
        'path' => $basePath,
        'mkdir' => is_dir($basePath),
        'write' => $written !== false,
        'exists' => $exists,
        'readable' => $readable,
        'content' => $exists ? file_get_contents($testFile) : null,
    ];
}

$results = [];
$results[] = testPath('/home/busnisscard/public_html/fitcure.online');
$results[] = testPath('/home/busnisscard/fitcure.online');
$results[] = testPath('/home/busnisscard/public_html/fitcure');

echo "Write test results:\n\n";
foreach ($results as $r) {
    echo "Path: {$r['path']}\n";
    echo "  mkdir: " . ($r['mkdir'] ? '✅' : '❌') . "\n";
    echo "  write: " . ($r['write'] ? '✅' : '❌') . "\n";
    echo "  exists: " . ($r['exists'] ? '✅' : '❌') . "\n";
    echo "  readable: " . ($r['readable'] ? '✅' : '❌') . "\n";
    echo "  content: " . ($r['content'] ?? 'N/A') . "\n\n";
}

// Clean up test files
foreach ($results as $r) {
    $tf = "{$r['path']}/__fitcure_test.txt";
    if (file_exists($tf)) @unlink($tf);
}

// Now check the actual document root of fitcure.online by examining Apache config
echo "PHP info:\n";
echo "USER: " . (getenv('USER') ?: get_current_user()) . "\n";
echo "HOME: " . (getenv('HOME') ?: 'N/A') . "\n";

// Try to find fitcure.online virtual host config
$possibleConfigs = [
    '/etc/apache2/sites-enabled/fitcure.online.conf',
    '/etc/apache2/sites-available/fitcure.online.conf',
    '/etc/httpd/sites-enabled/fitcure.online.conf',
    '/etc/httpd/sites-available/fitcure.online.conf',
    '/usr/local/apache/conf/sites/fitcure.online.conf',
];
echo "\nApache config files:\n";
foreach ($possibleConfigs as $c) {
    echo (file_exists($c) ? '✅' : '❌') . " $c\n";
}

// Check cPanel userdata
$userdataDirs = [
    '/var/cpanel/userdata/busnisscard/',
    '/home/busnisscard/.cpanel/',
];
echo "\ncPanel userdata:\n";
foreach ($userdataDirs as $d) {
    if (is_dir($d)) {
        echo "✅ $d\n";
        $files = scandir($d);
        $fitcureFiles = preg_grep('/fitcure/i', $files);
        if ($fitcureFiles) {
            echo "   Files: " . implode(', ', $fitcureFiles) . "\n";
            // Read the first one
            foreach ($fitcureFiles as $ff) {
                $content = file_get_contents("$d/$ff");
                if (preg_match('/documentroot:\s*(\S+)/i', $content, $m)) {
                    echo "   DocumentRoot: {$m[1]}\n";
                }
            }
        } else {
            echo "   No fitcure files\n";
            echo "   All files: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
        }
    } else {
        echo "❌ $d\n";
    }
}
