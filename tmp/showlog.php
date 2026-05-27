<?php
$log = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($log)) { echo "No log file\n"; exit; }
$lines = file($log);
$show = array_slice($lines, -60);
echo "<pre>";
foreach ($show as $l) echo htmlspecialchars($l);
echo "</pre>";
