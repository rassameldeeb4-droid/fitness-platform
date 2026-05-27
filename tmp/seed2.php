<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
echo "<pre>";

// === TRAINERS ===
echo "Adding trainers...\n";
$trainers = [
    ['name'=>'محمد عبدالرحمن','email'=>'trainer1@test.com','specialty'=>'تمارين قوة','bio'=>'مدرب معتمد بخبرة ١٠ سنوات في تمارين القوة وبناء العضلات','certification'=>'مدرب معتمد دوليًا','experience_years'=>10,'rating'=>4.8,'review_count'=>45],
    ['name'=>'أحمد السيد','email'=>'trainer2@test.com','specialty'=>'تمارين كارديو ورشاقة','bio'=>'متخصص في تمارين الكارديو وفقدان الوزن','certification'=>'شهادة NASM','experience_years'=>6,'rating'=>4.5,'review_count'=>32],
];
foreach ($trainers as $t) {
    if (DB::table('users')->where('email', $t['email'])->first()) { echo "  {$t['name']} exists\n"; continue; }
    $uid = DB::table('users')->insertGetId([
        'name'=>$t['name'],'email'=>$t['email'],'password'=>Hash::make('password'),'role'=>'trainer','phone'=>'05'.rand(10000000,99999999),'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')
    ]);
    DB::table('trainer_profiles')->insert([
        'user_id'=>$uid,'specialty'=>$t['specialty'],'bio'=>$t['bio'],'certification'=>$t['certification'],
        'experience_years'=>$t['experience_years'],'rating'=>$t['rating'],'review_count'=>$t['review_count'],'available'=>true,
        'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),
    ]);
    echo "  Added trainer: {$t['name']}\n";
}

// === DOCTORS ===
echo "\nAdding doctors...\n";
$doctors = [
    ['name'=>'د. أحمد العلي','email'=>'doctor1@test.com','specialty'=>'طب رياضي','bio'=>'استشاري الطب الرياضي وإصابات الملاعب'],
    ['name'=>'د. سارة الخالد','email'=>'doctor2@test.com','specialty'=>'تغذية علاجية','bio'=>'أخصائية التغذية العلاجية والسمنة'],
];
foreach ($doctors as $d) {
    if (DB::table('users')->where('email', $d['email'])->first()) { echo "  {$d['name']} exists\n"; continue; }
    DB::table('users')->insert([
        'name'=>$d['name'],'email'=>$d['email'],'password'=>Hash::make('password'),'role'=>'doctor','phone'=>'05'.rand(10000000,99999999),'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'),
    ]);
    echo "  Added doctor: {$d['name']}\n";
}

// === GYMS ===
echo "\nAdding gyms...\n";
$gyms = [
    ['name'=>'Golds Gym الرياض','city'=>'الرياض','address'=>'الرياض - حي العليا','phone'=>'0112345678'],
    ['name'=>'Fitness Time جدة','city'=>'جدة','address'=>'جدة - شارع التحلية','phone'=>'0123456789'],
    ['name'=>'FitZone الدمام','city'=>'الدمام','address'=>'الدمام - حي الفيصلية','phone'=>'0134567890'],
];
foreach ($gyms as $g) {
    $exists = DB::table('gyms')->where('name', $g['name'])->first();
    if ($exists) { echo "  {$g['name']} exists\n"; continue; }
    DB::table('gyms')->insert(array_merge($g, ['created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]));
    echo "  Added gym: {$g['name']}\n";
}

// === PACKAGES ===
echo "\nAdding packages...\n";
$packages = [
    ['name'=>'الباقة الأساسية','price'=>199,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['تمارين مفتوحة','متابعة أسبوعية']),'max_bookings'=>12,'has_trainer'=>false,'has_nutrition'=>false,'has_ai'=>false,'badge'=>null],
    ['name'=>'الباقة المتقدمة','price'=>399,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['تمارين مفتوحة','مدرب شخصي','نظام غذائي']),'max_bookings'=>20,'has_trainer'=>true,'has_nutrition'=>true,'has_ai'=>false,'badge'=>'الأكثر مبيعًا'],
    ['name'=>'الباقة الشاملة','price'=>599,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['مدرب شخصي','نظام غذائي','محلل طعام ذكي','جلسات علاج طبيعي']),'max_bookings'=>30,'has_trainer'=>true,'has_nutrition'=>true,'has_ai'=>true,'badge'=>'مميزة'],
    ['name'=>'باقة الـ ٦ شهور','price'=>999,'duration_days'=>180,'type'=>'half_yearly','features'=>json_encode(['كل شيء في الباقة الشاملة','خصم ٣٠٪','حجز أولوية']),'max_bookings'=>999,'has_trainer'=>true,'has_nutrition'=>true,'has_ai'=>true,'badge'=>'أفضل قيمة'],
];
foreach ($packages as $p) {
    $exists = DB::table('packages')->where('name', $p['name'])->first();
    if ($exists) { echo "  {$p['name']} exists\n"; continue; }
    DB::table('packages')->insert(array_merge($p, ['is_active'=>true,'description'=>$p['name'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]));
    echo "  Added package: {$p['name']}\n";
}

// === SAMPLE ORDERS ===
echo "\nAdding sample orders...\n";
$members = DB::table('users')->where('role', 'member')->get();
$products = DB::table('products')->get();
$statuses = ['pending','confirmed','shipped','delivered','cancelled'];
if ($members->count() > 0 && $products->count() > 0) {
    for ($i = 0; $i < 5; $i++) {
        $member = $members->random();
        $product = $products->random();
        $qty = rand(1, 3);
        $total = $product->price * $qty;
        $oid = DB::table('orders')->insertGetId([
            'user_id' => $member->id,
            'total' => $total,
            'status' => $statuses[array_rand($statuses)],
            'payment_method' => rand(0,1) ? 'credit_card' : 'bank_transfer',
            'created_at' => date('Y-m-d H:i:s', strtotime('-'.rand(1,14).' days')),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('order_items')->insert([
            'order_id' => $oid,
            'product_id' => $product->id,
            'quantity' => $qty,
            'price' => $product->price,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        echo "  Order #$oid for {$member->name} - {$product->name} x{$qty}\n";
    }
}

echo "\nDone!</pre>";
