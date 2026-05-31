<?php
// v2 - deploy fitcure with all checks
// Step 1: Probe the server config
// Step 2: Deploy platform if needed

echo "=== Step 1: Server Probe ===\n\n";

// Find where files end up
$probeId = time();
$testFiles = [];

$paths = [
    '/home/busnisscard/public_html/fitcure.online' => 1,
    '/home/busnisscard/fitcure.online' => 2,
    '/home/busnisscard/public_html/fitcure' => 3,
];

foreach ($paths as $base => $id) {
    @mkdir($base, 0755, true);
    $tf = "$base/fcP{$id}_$probeId.php";
    file_put_contents($tf, '<?php echo "P'.$id.'_'.$probeId.'";');
    $testFiles[$id] = $tf;
    echo "  Created: $tf\n";
    
    // Try accessing via HTTP
    $paths2 = [
        "https://fitcure.online/fcP{$id}_$probeId.php",
        "https://busnisscard.com/fcP{$id}_$probeId.php",
        "https://busnisscard.com/../fitcure.online/fcP{$id}_$probeId.php",
    ];
    foreach ($paths2 as $url) {
        $ctx = stream_context_create(['http' => ['timeout' => 3]]);
        $content = @file_get_contents($url, false, $ctx);
        $code = isset($http_response_header) ? explode(' ', $http_response_header[0])[1] : 'ERR';
        echo "    [$code] $url -> " . ($content ?: '-') . "\n";
    }
}

// Cleanup
foreach ($testFiles as $tf) @unlink($tf);

echo "\n=== Step 2: cPanel Check ===\n";
$mainConf = @file_get_contents('/var/cpanel/userdata/busnisscard/main');
if ($mainConf) {
    if (preg_match('/documentroot:\s*(\S+)/i', $mainConf, $m)) {
        echo "Main DocumentRoot: {$m[1]}\n";
    }
    // Check for addon domains
    preg_match_all('/^(?:domain|serveralias|alias):\s*(\S+)/im', $mainConf, $aliases);
    echo "All domains/aliases: " . implode(', ', $aliases[1]) . "\n";
}

echo "\n=== Done ===\n";
