<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
header('Content-Type: text/plain; charset=utf-8');

echo "=== Data Check ===\n\n";

echo "Conversations: " . DB::table('conversations')->count() . "\n";
echo "Messages: " . DB::table('messages')->count() . "\n";
echo "TimelineEvents: " . DB::table('timeline_events')->count() . "\n\n";

echo "--- Conversations per trainer ---\n";
$convs = DB::table('conversations')
    ->join('users as t', 'conversations.trainer_id', '=', 't.id')
    ->join('users as m', 'conversations.member_id', '=', 'm.id')
    ->select('t.name as trainer', 't.email as trainer_email', 'm.name as member', 'conversations.created_at')
    ->orderBy('t.name')
    ->get();
foreach ($convs as $c) {
    echo "  {$c->trainer} ({$c->trainer_email}) <-> {$c->member}\n";
}

echo "\n--- Timeline events per type ---\n";
$events = DB::table('timeline_events')
    ->select('type', DB::raw('count(*) as total'))
    ->groupBy('type')
    ->get();
foreach ($events as $e) {
    echo "  {$e->type}: {$e->total}\n";
}

echo "\n=== Done ===\n";