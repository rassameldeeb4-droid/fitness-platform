<?php
// Search ALL cPanel accounts for fitcure
echo "=== All cPanel accounts and domains ===\n";
$userdataDirs = glob('/var/cpanel/userdata/*');
foreach ($userdataDirs as $ud) {
    if (!is_dir($ud)) continue;
    $account = basename($ud);
    foreach (scandir($ud) as $f) {
        if ($f === '.' || $f === '..' || str_ends_with($f, '.cache')) continue;
        $c = file_get_contents("$ud/$f");
        if (preg_match('/documentroot:\s*(\S+)/i', $c, $m)) {
            $dr = $m[1];
            $domain = '';
            if (preg_match('/domain:\s*(\S+)/i', $c, $dm)) $domain = $dm[1];
            $hasCgi = is_dir("$dr/cgi-bin") ? 'cgi' : '';
            $cgiTime = $hasCgi ? date('Y-m-d H:i', filemtime("$dr/cgi-bin")) : '';
            echo "  [$account] $domain -> $dr $hasCgi $cgiTime\n";
        }
    }
}

echo "\n=== Check ALL home dirs for cgi-bin with 2026-05-30 ===\n";
foreach (glob('/home/*') as $home) {
    $cgiDirs = [
        "$home/public_html/cgi-bin",
        "$home/cgi-bin",
        "$home/fitcure.online/cgi-bin",
        "$home/public_html/fitcure.online/cgi-bin",
    ];
    foreach ($cgiDirs as $cgi) {
        if (is_dir($cgi)) {
            $ts = date('Y-m-d H:i:s', filemtime($cgi));
            if (str_starts_with($ts, '2026-05-30')) {
                $parent = dirname($cgi);
                echo "  ✅ $parent (mtime: $ts)\n";
                // Write test file
                $testContent = '<?php echo "ROOT:' . $parent . '";';
                $testFile = "$parent/__fc_final.php";
                file_put_contents($testFile, $testContent);
            }
        }
    }
}

echo "\n=== Check if test files are accessible ===\n";
$ctx = stream_context_create(['http' => ['timeout' => 3]]);
$content = @file_get_contents("https://fitcure.online/__fc_final.php", false, $ctx);
echo "fitcure.online/__fc_final.php -> " . ($content ?: 'NOT ACCESSIBLE') . "\n";

$content2 = @file_get_contents("https://busnisscard.com/__fc_final.php", false, $ctx);
echo "busnisscard.com/__fc_final.php -> " . ($content2 ?: 'NOT ACCESSIBLE') . "\n";

// Cleanup
foreach (glob('/home/*') as $home) {
    @unlink("$home/public_html/__fc_final.php");
    @unlink("$home/__fc_final.php");
    @unlink("$home/fitcure.online/__fc_final.php");
    @unlink("$home/public_html/fitcure.online/__fc_final.php");
}
