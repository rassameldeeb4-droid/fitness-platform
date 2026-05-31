<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\TimelineEvent;
use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain; charset=utf-8');
echo "=== Seed Demo Data ===\n\n";

// Find users by email
$trainers = [];
foreach (['trainer1@test.com', 'trainer2@test.com'] as $e) {
    $u = User::where('email', $e)->first();
    if ($u) $trainers[] = $u;
}
$doctors = [];
foreach (['doctor1@test.com', 'doctor2@test.com'] as $e) {
    $u = User::where('email', $e)->first();
    if ($u) $doctors[] = $u;
}
$members = [];
foreach (['ahmed@test.com', 'sara@test.com', 'khaled@test.com', 'noura@test.com', 'faisal@test.com', 'trainer8@test.com'] as $e) {
    $u = User::where('email', $e)->first();
    if ($u) $members[] = $u;
}

echo "Found: " . count($trainers) . " trainers, " . count($doctors) . " doctors, " . count($members) . " members\n\n";

// Demo messages
$messagesPool = [
    ['مدرب', 'مرحباً! نبدأ التدريب اليوم؟'],
    ['عضو', 'إن شاء الله! أنا جاهز 💪'],
    ['مدرب', 'خلصت التمرين؟'],
    ['عضو', 'أيوه ١٠٠٪ شد حيلك'],
    ['مدرب', 'ممتاز! زود الوزن ٢ كيلو يوم الإثنين'],
    ['عضو', 'هل النظام الغذائي مناسب؟'],
    ['مدرب', 'نعم بس زود شوية بروتين'],
    ['عضو', 'تم 🔥'],
    ['مدرب', 'شو آخر قياساتك؟'],
    ['عضو', 'نزلت ٢ كيلو هذا الأسبوع الحمدلله 🎉'],
    ['مدرب', 'ممتاز جداً! استمر 👏'],
    ['عضو', 'فيه تمرين جديد النهارده؟'],
    ['مدرب', 'أيوه هنغير الجدول الأسبوع الجاي'],
    ['عضو', 'أنا حبيت التمارين مره'],
    ['مدرب', 'الله يوفقك شد حيلك'],
];

$count = 0;

// Create conversations for trainers
foreach ($trainers as $trainer) {
    foreach ($members as $i => $member) {
        $existing = Conversation::where('trainer_id', $trainer->id)->where('member_id', $member->id)->first();
        if ($existing) continue;

        $conv = Conversation::create([
            'trainer_id' => $trainer->id,
            'member_id' => $member->id,
            'last_message_at' => now()->subHours(rand(0, 48)),
        ]);
        $count++;

        // Add some messages
        $msgCount = rand(2, 5);
        for ($m = 0; $m < $msgCount; $m++) {
            $idx = array_rand($messagesPool);
            $senderRole = $messagesPool[$idx][0];
            $senderId = $senderRole === 'مدرب' ? $trainer->id : $member->id;
            Message::create([
                'conversation_id' => $conv->id,
                'sender_id' => $senderId,
                'message' => $messagesPool[$idx][1],
                'type' => 'text',
                'created_at' => now()->subHours(rand(0, 48))->subMinutes(rand(0, 59)),
            ]);
        }
    }
}

// Create conversations for doctors
foreach ($doctors as $doctor) {
    foreach ($members as $i => $member) {
        $existing = Conversation::where('trainer_id', $doctor->id)->where('member_id', $member->id)->first();
        if ($existing) continue;

        $conv = Conversation::create([
            'trainer_id' => $doctor->id,
            'member_id' => $member->id,
            'last_message_at' => now()->subHours(rand(0, 72)),
        ]);
        $count++;

        $msgCount = rand(1, 3);
        for ($m = 0; $m < $msgCount; $m++) {
            $idx = array_rand($messagesPool);
            $senderRole = $messagesPool[$idx][0];
            $senderId = $senderRole === 'مدرب' ? $doctor->id : $member->id;
            Message::create([
                'conversation_id' => $conv->id,
                'sender_id' => $senderId,
                'message' => $messagesPool[$idx][1],
                'type' => 'text',
                'created_at' => now()->subHours(rand(0, 72))->subMinutes(rand(0, 59)),
            ]);
        }
    }
}

echo "Created $count conversations with messages\n";

// Timeline events
$eventDefs = [
    ['type' => 'member_added', 'title' => 'تم إضافة متدرب جديد', 'desc' => 'تم إضافة {member} للتدريب مع {trainer}'],
    ['type' => 'nutrition_plan_assigned', 'title' => 'تم تعيين خطة غذائية', 'desc' => 'تم تعيين خطة غذائية لـ {member} بواسطة {trainer}'],
    ['type' => 'workout_plan_assigned', 'title' => 'تم تعيين خطة تمارين', 'desc' => 'تم تعيين خطة تمارين لـ {member} بواسطة {trainer}'],
    ['type' => 'progress_logged', 'title' => 'تسجيل قياسات جديدة', 'desc' => '{member} سجل قياسات جديدة: الوزن {weight} كجم'],
];

$eventCount = 0;
$now = now();

foreach ($trainers as $trainer) {
    foreach ($members as $i => $member) {
        // member_added event
        $ed = $eventDefs[0];
        TimelineEvent::create([
            'user_id' => $trainer->id,
            'related_user_id' => $member->id,
            'type' => $ed['type'],
            'title' => $ed['title'],
            'description' => str_replace(['{member}', '{trainer}'], [$member->name, $trainer->name], $ed['desc']),
            'is_read' => false,
            'created_at' => $now->subDays(rand(1, 30)),
        ]);
        $eventCount++;

        // plan assigned event
        $ed = $eventDefs[rand(1, 2)];
        TimelineEvent::create([
            'user_id' => $trainer->id,
            'related_user_id' => $member->id,
            'type' => $ed['type'],
            'title' => $ed['title'],
            'description' => str_replace(['{member}', '{trainer}'], [$member->name, $trainer->name], $ed['desc']),
            'metadata' => ['plan_id' => rand(1, 5)],
            'is_read' => false,
            'created_at' => $now->subDays(rand(1, 15)),
        ]);
        $eventCount++;

        // progress_logged event
        $ed = $eventDefs[3];
        TimelineEvent::create([
            'user_id' => $member->id,
            'related_user_id' => $trainer->id,
            'type' => $ed['type'],
            'title' => $ed['title'],
            'description' => str_replace(['{member}', '{weight}'], [$member->name, rand(70, 90)], $ed['desc']),
            'is_read' => false,
            'created_at' => $now->subHours(rand(1, 72)),
        ]);
        $eventCount++;
    }
}

echo "Created $eventCount timeline events\n";
echo "\n=== Done ===\n";