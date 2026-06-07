<?php
$ctrl = __DIR__ . '/../app/Http/Controllers/Doctor/DashboardController.php';
echo "=== Controller ===";
echo file_get_contents($ctrl);
echo "\n=== View ===";
$view = __DIR__ . '/../resources/views/doctor/dashboard.blade.php';
echo file_get_contents($view);
