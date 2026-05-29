<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
echo "<pre>Accounts on server:\n\n";
$users = DB::table('users')->select('id','name','email','role')->get();
foreach ($users as $u) {
    echo "{$u->id}\t{$u->role}\t{$u->email}\t{$u->name}\n";
}
echo "\nAll passwords: password\n";
