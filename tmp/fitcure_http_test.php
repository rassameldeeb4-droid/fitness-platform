<?php
// Step 1: Find the actual fitcure document root
// Step 2: Deploy there

echo "=== Finding fitcure.online document root ===\n\n";

// Search all home directories for cgi-bin
$homes = glob('/home/*');
echo "Home directories found:\n";
foreach ($homes as $h) {
    $cgi = "$h/public_html/cgi-bin";
    $cgi2 = "$h/cgi-bin";
    
    if (is_dir($cgi)) {
        $mtime = date('Y-m-d H:i:s', filemtime($cgi));
        echo "  $cgi ($mtime)\n";
    }
    if (is_dir($cgi2)) {
        $mtime = date('Y-m-d H:i:s', filemtime($cgi2));
        echo "  $cgi2 ($mtime)\n";
    }
}

echo "\n=== Searching for __fitcure_test.txt in all home dirs ===\n";
foreach ($homes as $h) {
    $files = glob("$h/*/__fitcure_test.txt");
    $files2 = glob("$h/__fitcure_test.txt");
    foreach (array_merge($files, $files2) as $f) {
        echo "  Found: $f\n";
        echo "  Content: " . file_get_contents($f) . "\n";
    }
}

echo "\n=== Checking all cPanel userdata dirs ===\n";
$userdataDirs = glob('/var/cpanel/userdata/*');
foreach ($userdataDirs as $ud) {
    if (is_dir($ud)) {
        foreach (scandir($ud) as $f) {
            if ($f === '.' || $f === '..' || str_ends_with($f, '.cache')) continue;
            $c = file_get_contents("$ud/$f");
            if (preg_match('/documentroot:\s*(\S+)/i', $c, $m)) {
                $dr = $m[1];
                $cgiTest = "$dr/cgi-bin";
                if (is_dir($cgiTest)) {
                    $domain = basename($f, '.cache');
                    if (str_starts_with($domain, 'fitcure')) {
                        echo "  ✅ $domain -> $dr\n";
                    }
                }
            }
        }
    }
}

echo "\n=== Writing test files to candidate roots and checking via HTTP ===\n";
$testId = time();
foreach ($homes as $h) {
    $candidates = [
        "$h/public_html",
        "$h/fitcure.online",
        "$h/public_html/fitcure.online",
    ];
    foreach ($candidates as $p) {
        if (!is_dir($p)) continue;
        $tf = "$p/_fctest_$testId.txt";
        file_put_contents($tf, "path:$p");
        
        // Try via HTTP
        $url = "https://fitcure.online/_fctest_$testId.txt";
        $ctx = stream_context_create(['http' => ['timeout' => 2]]);
        $content = @file_get_contents($url, false, $ctx);
        if ($content) {
            echo "  ✅ fitcure.online responds from: $p\n";
        }
        @unlink($tf);
    }
}

echo "\n=== Done ===\n";
