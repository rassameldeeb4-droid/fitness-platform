<?php
// Set OPENAI_API_KEY in .env
$keys = [
    ['key' => 'OPENAI_API_KEY', 'val' => base64_decode('c2stcHJvai1xRUxFUFBZbHI2STh5My1RV21nR1VLb3FVRndMLThydFBnOE53MFBxdmZTUDFBX2NlYmF6a0NzaUU0SmdVbFVWM1BSUGRIazh3RVQzQmxia0ZKaDY3S1dncHg0Y1ZTX04wSUFZem8zZXMzUFd0cjNCZHVCbzJxVUZ2YXpjSnI4dzhySVpJY1J3Y0hyc0NYRUFaM1FBYzFYY2hSd0E=')],
];
$envFile = __DIR__ . '/../.env';
$content = file_exists($envFile) ? file_get_contents($envFile) : '';
foreach ($keys as $k) {
    if ($k['val'] === '') continue;
    if (preg_match('/^' . $k['key'] . '=.*$/m', $content)) {
        $content = preg_replace('/^' . $k['key'] . '=.*$/m', $k['key'] . '=' . $k['val'], $content);
    } elseif (!str_contains($content, $k['key'] . '=')) {
        $content .= "\n" . $k['key'] . '=' . $k['val'] . "\n";
    }
}
file_put_contents($envFile, $content);
echo "API keys updated\n";
