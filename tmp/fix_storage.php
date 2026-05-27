<?php
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/../public/storage';
echo "<pre>Fixing storage symlink...\n";
echo "Target: $target " . (is_dir($target) ? "✅ exists" : "❌ not found") . "\n";
echo "Link: $link " . (file_exists($link) ? "✅ exists" : "❌ not found") . "\n";
if (!file_exists($link)) {
    if (is_dir($target)) {
        if (symlink($target, $link)) echo "Symlink created ✅\n";
        else echo "Failed to create symlink ❌\n";
    } else echo "Target doesn't exist. Creating...\n";
} else echo "Symlink already exists ✅\n";
echo "\nDone!</pre>";
