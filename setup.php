<?php
$env = "APP_NAME=Fitness\n";
$env .= "APP_ENV=production\n";
$env .= "APP_KEY=\n";
$env .= "APP_DEBUG=false\n";
$env .= "APP_URL=https://busnisscard.com\n";
$env .= "\n";
$env .= "DB_CONNECTION=mysql\n";
$env .= "DB_HOST=127.0.0.1\n";
$env .= "DB_PORT=3306\n";
$env .= "DB_DATABASE=busnisscard_FitCore\n";
$env .= "DB_USERNAME=busnisscard_fitcore\n";
$env .= "DB_PASSWORD=')@h{Mb$=+C2zc8Bl'\n";
$env .= "\n";
$env .= "SESSION_DRIVER=database\n";
$env .= "SESSION_LIFETIME=120\n";
$env .= "BROADCAST_CONNECTION=log\n";
$env .= "FILESYSTEM_DISK=local\n";
$env .= "QUEUE_CONNECTION=database\n";
$env .= "CACHE_STORE=database\n";

$path = __DIR__ . '/.env';
file_put_contents($path, $env);
echo "✅ .env created<br>";

$output = shell_exec('php artisan key:generate --force 2>&1');
echo "✅ Key: $output<br>";

$output = shell_exec('php artisan storage:link --force 2>&1');
echo "✅ Storage: $output<br>";

$output = shell_exec('php artisan migrate --force 2>&1');
echo "✅ Migrate: $output<br>";

echo "<hr>Done! <a href='/'>Go to site</a>";
