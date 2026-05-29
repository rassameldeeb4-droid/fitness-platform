<?php
$key = base64_decode('c2stcHJvai1xRUxFUFBZbHI2STh5My1RV21nR1VLb3FVRndMLThydFBnOE53MFBxdmZTUDFBX2NlYmF6a0NzaUU0SmdVbFVWM1BSUGRIazh3RVQzQmxia0ZKaDY3S1dncHg0Y1ZTX04wSUFZem8zZXMzUFd0cjNCZHVCbzJxVUZ2YXpjSnI4dzhySVpJY1J3Y0hyc0NYRUFaM1FBYzFYY2hSd0E=');
$f = __DIR__ . '/../.env';
$c = file_exists($f) ? file_get_contents($f) : '';
$c = preg_replace('/^OPENAI_API_KEY=.*$/m', "OPENAI_API_KEY=$key", $c);
if (!str_contains($c, 'OPENAI_API_KEY=')) $c .= "\nOPENAI_API_KEY=$key\n";
file_put_contents($f, $c);
echo "done\n";
