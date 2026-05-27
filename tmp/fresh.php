<?php
// Force fresh download and execute
$code = file_get_contents('https://raw.githubusercontent.com/rassameldeeb4-droid/fitness-platform/main/tmp/fix_all.php?cb=' . time());
if (!$code) die("Download failed");
file_put_contents(__FILE__, $code);
// Now we have the latest code, execute it
eval('?>' . $code);
