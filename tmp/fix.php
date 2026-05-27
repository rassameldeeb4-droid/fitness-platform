<?php
echo "<pre>";

$public = __DIR__ . '/../public';
$htaccess = $public . '/.htaccess';

echo "Creating .htaccess...\n";
file_put_contents($htaccess, "RewriteEngine On\nRewriteBase /platform/public/\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteRule ^ index.php [L]\n");
echo "  Done\n";

$guest = __DIR__ . '/../resources/views/layouts/guest.blade.php';
$content = file_get_contents($guest);
$content = str_replace(
    '@vite([\'resources/css/app.css\', \'resources/js/app.js\'])',
    '{{-- Vite disabled --}}',
    $content
);
file_put_contents($guest, $content);
echo "  Vite removed from guest layout\n";

$appLayout = __DIR__ . '/../resources/views/layouts/app.blade.php';
$content = file_get_contents($appLayout);
$content = str_replace(
    '@vite([\'resources/css/app.css\'])',
    '{{-- Vite disabled --}}',
    $content
);
file_put_contents($appLayout, $content);
echo "  Vite removed from app layout\n";

$viewsDir = __DIR__ . '/../storage/framework/views';
if (is_dir($viewsDir)) {
    array_map('unlink', glob($viewsDir . '/*'));
}
echo "  View cache cleared\n";

echo "All fixes applied! Try accessing the site now.</pre>";
