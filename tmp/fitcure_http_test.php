<?php
// Direct HTTP test for fitcure paths

$paths = [
    '/home/busnisscard/public_html/fitcure.online',
    '/home/busnisscard/fitcure.online',
];

$unique = time() . rand(100,999);

foreach ($paths as $base) {
    @mkdir($base, 0755, true);
    file_put_contents("$base/fc$unique.php", '<?php echo "OK:' . basename($base) . '";');
    file_put_contents("$base/fc$unique.html", "html:" . basename($base));
}

echo "Test ID: $unique\n\nHTTP tests:\n";

$tests = [
    "https://fitcure.online/fc$unique.php",
    "https://fitcure.online/fc$unique.html",
    "https://fitcure.online/public_html/fitcure.online/fc$unique.php",
    "https://fitcure.online/public_html/fitcure.online/fc$unique.html",
    "https://busnisscard.com/fc$unique.php",
    "https://busnisscard.com/fitcure.online/fc$unique.php",
];

foreach ($tests as $url) {
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'follow_location' => false]]);
    $content = @file_get_contents($url, false, $ctx);
    $code = isset($http_response_header) ? explode(' ', $http_response_header[0])[1] : 'ERR';
    $short = $content ? substr($content, 0, 100) : '-';
    echo "  $code: $url -> $short\n";
}

// Cleanup
foreach ($paths as $base) {
    @unlink("$base/fc$unique.php");
    @unlink("$base/fc$unique.html");
}
