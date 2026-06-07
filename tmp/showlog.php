<?php
$log = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($log)) { echo "No log file\n"; exit; }
$lines = file($log);
$showAll = ($_GET['all'] ?? '') === '1';
if ($showAll) {
    $show = $lines;
} else {
    $take = max(200, count($lines));
    $show = array_slice($lines, -$take);
}
echo "<pre>";
foreach ($show as $l) echo htmlspecialchars($l);
echo "</pre>";
if ($showAll) {
    echo "<p>END</p>";
}
