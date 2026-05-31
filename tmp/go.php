<?php
// Emergency deploy: find and setup fitcure.online
// This file overwrites go.php which is already on the server

$testId = time();
$found = false;

// Search all home directories for the right cgi-bin
foreach (glob('/home/*') as $home) {
    foreach (["$home/fitcure.online", "$home/public_html/fitcure.online", "$home/public_html"] as $dir) {
        $cgi = "$dir/cgi-bin";
        if (is_dir($cgi) && date('Y-m-d', filemtime($cgi)) === '2026-05-30') {
            echo "FOUND: $dir (cgi-bin matches fitcure.online)\n";
            file_put_contents("$dir/_fc_greeting.txt", "FitCure Online - $testId");
            
            // Try HTTP
            $ctx = stream_context_create(['http' => ['timeout' => 3]]);
            $resp = @file_get_contents("https://fitcure.online/_fc_greeting.txt", false, $ctx);
            echo "HTTP test: " . ($resp ? "WORKS! Content: $resp" : "FAILED") . "\n";
            
            if ($resp) {
                echo "\nDocument root for fitcure.online is: $dir\n";
                $found = true;
            }
            @unlink("$dir/_fc_greeting.txt");
        }
    }
}

if (!$found) {
    echo "NOT FOUND. Listing all cgi-bin dirs:\n";
    foreach (glob('/home/*') as $home) {
        foreach (glob("$home/*/cgi-bin", GLOB_ONLYDIR) as $cgi) {
            echo "  $cgi (" . date('Y-m-d H:i:s', filemtime($cgi)) . ")\n";
        }
        foreach (glob("$home/cgi-bin", GLOB_ONLYDIR) as $cgi) {
            echo "  $cgi (" . date('Y-m-d H:i:s', filemtime($cgi)) . ")\n";
        }
    }
}
