<?php
// Clean deploy approach: write files and check via HTTP

$paths = [
    '/home/busnisscard/public_html/fitcure.online',
    '/home/busnisscard/fitcure.online',
];

$unique = '_' . time();

// Write test files to all candidate paths
foreach ($paths as $base) {
    @mkdir($base, 0755, true);
    file_put_contents("$base/fc_test$unique.php", '<?php echo "FROM:' . $base . '";');
    file_put_contents("$base/fc_test$unique.html", "html:$base");
}

echo "Test ID: $unique\n\n";

// Now try to access via HTTP
echo "HTTP Test Results:\n";
$urls = [
    "https://fitcure.online/fc_test$unique.php",
    "https://fitcure.online/fc_test$unique.html",
    "https://busnisscard.com/fc_test$unique.php",
];
foreach ($urls as $url) {
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $content = @file_get_contents($url, false, $ctx);
    $httpCode = $http_response_header ? (int)explode(' ', $http_response_header[0])[1] : 0;
    echo "  [$httpCode] $url\n";
    if ($content) {
        echo "    -> $content\n";
    }
}

// Clean up test files
foreach ($paths as $base) {
    @unlink("$base/fc_test$unique.php");
    @unlink("$base/fc_test$unique.html");
}

echo "\n=== Next step: decide which path ===\n";
