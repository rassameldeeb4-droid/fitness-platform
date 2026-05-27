<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
echo "<pre>";

echo "Adding gyms...\n";
$gyms = [
    ['name'=>'Golds Gym الرياض','city'=>'الرياض','address'=>'الرياض - حي العليا','phone'=>'0112345678'],
    ['name'=>'Fitness Time جدة','city'=>'جدة','address'=>'جدة - شارع التحلية','phone'=>'0123456789'],
    ['name'=>'FitZone الدمام','city'=>'الدمام','address'=>'الدمام - حي الفيصلية','phone'=>'0134567890'],
];
foreach ($gyms as $g) {
    if (DB::table('gyms')->where('name', $g['name'])->first()) { echo "  {$g['name']} exists\n"; continue; }
    DB::table('gyms')->insert(array_merge($g, ['created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]));
    echo "  Added: {$g['name']}\n";
}

echo "\nAdding packages...\n";
$packages = [
    ['name'=>'الباقة الأساسية','price'=>199,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['تمارين مفتوحة','متابعة أسبوعية']),'max_bookings'=>12],
    ['name'=>'الباقة المتقدمة','price'=>399,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['تمارين مفتوحة','مدرب شخصي','نظام غذائي']),'max_bookings'=>20,'badge'=>'الأكثر مبيعًا'],
    ['name'=>'الباقة الشاملة','price'=>599,'duration_days'=>30,'type'=>'monthly','features'=>json_encode(['مدرب شخصي','نظام غذائي','محلل طعام ذكي','جلسات علاج طبيعي']),'max_bookings'=>30,'badge'=>'مميزة'],
    ['name'=>'باقة الـ ٦ شهور','price'=>999,'duration_days'=>180,'type'=>'half_yearly','features'=>json_encode(['كل شيء في الباقة الشاملة','خصم ٣٠٪','حجز أولوية']),'max_bookings'=>999,'badge'=>'أفضل قيمة'],
];
foreach ($packages as $p) {
    if (DB::table('packages')->where('name', $p['name'])->first()) { echo "  {$p['name']} exists\n"; continue; }
    $premium = $p['name'] !== 'الباقة الأساسية';
    DB::table('packages')->insert(array_merge($p, ['is_active'=>true,'has_trainer'=>$premium,'has_nutrition'=>$premium,'has_ai'=>strpos($p['name'],'الشاملة')!==false||strpos($p['name'],'٦')!==false,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]));
    echo "  Added: {$p['name']}\n";
}

echo "\nAdding sample orders...\n";
$members = DB::table('users')->where('role','member')->get();
$products = DB::table('products')->get();
$statuses = ['pending','confirmed','shipped','delivered','cancelled'];
if ($members->count()>0 && $products->count()>0) {
    for ($i=0;$i<5;$i++) {
        $m = $members->random(); $p = $products->random(); $q = rand(1,3);
        $oid = DB::table('orders')->insertGetId(['user_id'=>$m->id,'total'=>$p->price*$q,'status'=>$statuses[array_rand($statuses)],'payment_method'=>rand(0,1)?'credit_card':'bank_transfer','created_at'=>date('Y-m-d H:i:s',strtotime('-'.rand(1,14).' days')),'updated_at'=>date('Y-m-d H:i:s')]);
        DB::table('order_items')->insert(['order_id'=>$oid,'product_id'=>$p->id,'quantity'=>$q,'price'=>$p->price,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
        echo "  Order #$oid: {$m->name} ← {$p->name} x{$q}\n";
    }
}
echo "\nDone!</pre>";
