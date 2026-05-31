<?php
// Find the actual document root for fitcure.online

echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";

$paths = [
    '/home/busnisscard/public_html/fitcure.online',
    '/home/busnisscard/fitcure.online',
    '/home/busnisscard/public_html/fitcure',
    '/home/busnisscard/fitcure',
];

foreach ($paths as $p) {
    if (is_dir($p)) {
        echo "✅ DIR EXISTS: $p\n";
        $files = scandir($p);
        echo "   Files: " . implode(', ', array_diff($files, ['.', '..'])) . "\n";
    } else {
        echo "❌ NOT FOUND: $p\n";
    }
}

// Check if the deploy_fitcure.php file exists
echo "\nChecking if deploy_fitcure.php ran...\n";
$deployScript = '/home/busnisscard/public_html/platform/tmp/deploy_fitcure.php';
echo "deploy_fitcure.php exists: " . (file_exists($deployScript) ? 'yes' : 'no') . "\n";

// Try creating a test file
$testDir = '/home/busnisscard/public_html/fitcure.online';
echo "\nAttempting to create $testDir...\n";
if (@mkdir($testDir, 0755, true)) {
    echo "  Created!\n";
    file_put_contents("$testDir/test.txt", "hello");
    echo "  test.txt written: " . (file_exists("$testDir/test.txt") ? 'yes' : 'no') . "\n";
} else {
    echo "  Failed to create\n";
    $parent = dirname($testDir);
    echo "  Parent $parent writable: " . (is_writable($parent) ? 'yes' : 'no') . "\n";
}
