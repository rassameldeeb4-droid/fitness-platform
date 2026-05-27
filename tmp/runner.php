<?php
// Download latest fix_all.php using Git commit SHA (no CDN cache)
$ctx = stream_context_create(['http'=>['header'=>'User-Agent: php']]);
$ref = json_decode(file_get_contents('https://api.github.com/repos/rassameldeeb4-droid/fitness-platform/git/refs/heads/main',false,$ctx),1);
$sha = $ref['object']['sha'];
$code = file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/'.$sha.'/tmp/fix_all.php');
if ($code) eval('?>'.$code);
else echo 'Download failed';
