<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gym;
use App\Models\MemberProfile;
use App\Models\TrainerProfile;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Product;
use App\Models\Food;
use App\Models\Exercise;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\NotificationSetting;
use App\Models\NutritionPlan;
use App\Models\WorkoutPlan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@fitness.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+966 50 000 0000',
        ]);

        // Gyms
        $gyms = [];
        foreach ([
            ['name'=>'صالة الرياض الرئيسية', 'city'=>'الرياض', 'address'=>'العليا', 'phone'=>'+966 11 200 0000', 'capacity'=>500, 'trainer_count'=>12],
            ['name'=>'صالة جدة', 'city'=>'جدة', 'address'=>'الروضة', 'phone'=>'+966 12 300 0000', 'capacity'=>350, 'trainer_count'=>8],
            ['name'=>'صالة الدمام', 'city'=>'الدمام', 'address'=>'الشاطئ', 'phone'=>'+966 13 400 0000', 'capacity'=>280, 'trainer_count'=>6],
            ['name'=>'صالة المدينة', 'city'=>'المدينة', 'address'=>'قرب المسجد النبوي', 'phone'=>'+966 14 500 0000', 'capacity'=>300, 'trainer_count'=>5],
        ] as $g) {
            $gyms[] = Gym::create($g);
        }

        // Trainer
        $trainer = User::create([
            'name' => 'محمد علي',
            'email' => 'mohamed@fitness.com',
            'password' => bcrypt('password'),
            'role' => 'trainer',
            'phone' => '+966 50 123 4567',
        ]);
        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialty' => 'كمال أجسام ولياقة بدنية',
            'bio' => 'مدرب معتمد بخبرة 8 سنوات في مجال كمال الأجسام والتغذية الرياضية',
            'certification' => 'ACSM',
            'experience_years' => 8,
            'rating' => 4.9,
            'review_count' => 47,
            'available' => true,
        ]);

        // Members
        $memberData = [
            ['name'=>'أحمد الغامدي','email'=>'ahmed@test.com','weight'=>89,'target'=>82,'goal'=>'weight_loss','height'=>178,'body_fat'=>21,'progress'=>65],
            ['name'=>'نورا السلمي','email'=>'noura@test.com','weight'=>62,'target'=>55,'goal'=>'toning','height'=>165,'body_fat'=>24,'progress'=>80],
            ['name'=>'فيصل المطيري','email'=>'faisal@test.com','weight'=>78,'target'=>80,'goal'=>'muscle_gain','height'=>175,'body_fat'=>18,'progress'=>45],
            ['name'=>'ريم الشهري','email'=>'reem@test.com','weight'=>57,'target'=>55,'goal'=>'general','height'=>162,'body_fat'=>22,'progress'=>90],
        ];

        $members = [];
        foreach ($memberData as $i => $md) {
            $user = User::create([
                'name' => $md['name'],
                'email' => $md['email'],
                'password' => bcrypt('password'),
                'role' => 'member',
            ]);
            MemberProfile::create([
                'user_id' => $user->id,
                'trainer_id' => $trainer->id,
                'gym_id' => $gyms[$i % 4]->id,
                'goal' => $md['goal'],
                'height' => $md['height'],
                'current_weight' => $md['weight'],
                'target_weight' => $md['target'],
                'body_fat' => $md['body_fat'],
                'progress_percentage' => $md['progress'],
                'activity_level' => 'moderate',
                'workout_days_per_week' => 4,
            ]);
            NotificationSetting::create(['user_id' => $user->id]);
            $members[] = $user;
        }

        // Packages
        Package::create(['name'=>'مجانية','description'=>'وصول أساسي للمنصة','price'=>0,'duration_days'=>30,'type'=>'free','max_bookings'=>3,'features'=>['وصول أساسي','تطبيق الجوال','3 تمارين/أسبوع']]);
        Package::create(['name'=>'شهرية','description'=>'باقة شهرية كاملة','price'=>149,'duration_days'=>30,'type'=>'monthly','max_bookings'=>20,'has_trainer'=>true,'has_nutrition'=>true,'features'=>['كل مميزات المجانية','مدرب شخصي','نظام غذائي','متابعة يومية']]);
        Package::create(['name'=>'بريميوم سنوية','description'=>'الباقة المميزة لمدة عام','price'=>999,'duration_days'=>365,'type'=>'yearly','max_bookings'=>50,'has_trainer'=>true,'has_nutrition'=>true,'has_ai'=>true,'badge'=>'الأكثر مبيعاً','features'=>['كل المميزات','أولوية الحجز','تحليل متقدم','تقارير شهرية']]);

        // Subscriptions
        $pkg3 = Package::where('type', 'yearly')->first();
        foreach ($members as $m) {
            Subscription::create([
                'user_id' => $m->id,
                'package_id' => $pkg3->id,
                'trainer_id' => $trainer->id,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'status' => 'active',
                'amount' => 999,
            ]);
        }

        // Foods
        $foodData = [
            ['name'=>'صدر دجاج مشوي','cat'=>'بروتين',165,31,0,3.6,0,'B6,B12'],
            ['name'=>'بيض مسلوق','cat'=>'بروتين',155,13,1.1,11,0,'D,B12,A'],
            ['name'=>'سلمون','cat'=>'بروتين',208,20,0,13,0,'D,B12,أوميغا3'],
            ['name'=>'تونة معلبة','cat'=>'بروتين',116,25,0,1,0,'B12,D'],
            ['name'=>'أرز أبيض','cat'=>'كارب',130,2.7,28,0.3,0.4,'B1'],
            ['name'=>'أرز بني','cat'=>'كارب',216,5,44,1.8,3.5,'B1,Mg'],
            ['name'=>'شوفان','cat'=>'كارب',389,17,66,7,10,'B1,Mg,Fe'],
            ['name'=>'بطاطا حلوة','cat'=>'كارب',86,1.6,20,0.1,3,'A,C,B6'],
            ['name'=>'موز','cat'=>'فاكهة',89,1.1,23,0.3,2.6,'B6,C,K'],
            ['name'=>'تفاح','cat'=>'فاكهة',52,0.3,14,0.2,2.4,'C,K'],
            ['name'=>'أفوكادو','cat'=>'دهون صحية',160,2,9,15,7,'K,E,B6'],
            ['name'=>'مكسرات مشكلة','cat'=>'دهون صحية',607,20,21,54,7,'E,Mg,B6'],
            ['name'=>'زبادي يوناني','cat'=>'ألبان',59,10,3.6,0.4,0,'B12,Ca'],
            ['name'=>'جبنة قريش','cat'=>'ألبان',98,11,3.4,4.3,0,'Ca,B12'],
            ['name'=>'بروكلي','cat'=>'خضار',34,2.8,7,0.4,2.6,'C,K,B9'],
            ['name'=>'سبانخ','cat'=>'خضار',23,2.9,3.6,0.4,2.2,'K,A,C,Fe'],
            ['name'=>'عدس مطبوخ','cat'=>'بقوليات',116,9,20,0.4,8,'B9,Fe,Mg'],
            ['name'=>'حليب كامل الدسم','cat'=>'ألبان',61,3.2,4.8,3.3,0,'D,Ca,B12'],
            ['name'=>'زيت زيتون','cat'=>'دهون صحية',884,0,0,100,0,'E,K'],
        ];
        foreach ($foodData as $f) {
            Food::create([
                'name' => $f['name'],
                'name_en' => '',
                'category' => $f['cat'],
                'calories_per_100g' => $f[0],
                'protein_per_100g' => $f[1],
                'carbs_per_100g' => $f[2],
                'fat_per_100g' => $f[3],
                'fiber_per_100g' => $f[4],
                'vitamins' => $f[5],
            ]);
        }

        // Exercises
        $exData = [
            ['name'=>'سكواد بالبار','cat'=>'أرجل','muscle'=>'رباعية الفخذ',3,'12','intermediate','https://www.youtube.com/embed/ultWZbUMPL8'],
            ['name'=>'بنش برس','cat'=>'صدر','muscle'=>'الصدر والترايسبس',4,'10','intermediate','https://www.youtube.com/embed/vcBig73ojpE'],
            ['name'=>'ديدليفت','cat'=>'ظهر','muscle'=>'أسفل الظهر',3,'8','advanced',''],
            ['name'=>'لانج','cat'=>'أرجل','muscle'=>'المؤخرة والفخذ',3,'15','beginner',''],
            ['name'=>'سحب علوي','cat'=>'ظهر','muscle'=>'عضلة الظهر العريضة',4,'10','intermediate',''],
            ['name'=>'كيرل دمبل','cat'=>'ذراع','muscle'=>'البايسبس',3,'12','beginner',''],
            ['name'=>'ضغط كتف','cat'=>'كتف','muscle'=>'الدالية',4,'12','intermediate',''],
            ['name'=>'رفع جانبي','cat'=>'كتف','muscle'=>'الدالية الجانبية',3,'15','beginner',''],
            ['name'=>'بلانك','cat'=>'بطن','muscle'=>'العرضي',3,'60','beginner',''],
            ['name'=>'ترايسبس كيبل','cat'=>'ذراع','muscle'=>'الترايسبس',3,'12','intermediate',''],
        ];
        foreach ($exData as $e) {
            Exercise::create([
                'name' => $e['name'],
                'category' => $e['cat'],
                'muscle_group' => $e['muscle'],
                'sets_default' => $e[0],
                'reps_default' => $e[1],
                'difficulty' => $e[2],
                'video_url' => $e[3],
            ]);
        }

        // Products
        $prodData = [
            ['بروتين واي الذهبي','Optimum Nutrition',189,220,'بروتين',4.8,'الأكثر مبيعاً'],
            ['كرياتين مونوهيدرات','MuscleTech',89,110,'قوة',4.7,''],
            ['BCAA نكهة البطيخ','Scivation',115,0,'تعافي',4.6,'جديد'],
            ['فيتامين D3+K2','Now Foods',55,0,'فيتامينات',4.9,''],
            ['أوميغا 3 زيت السمك','Nordic Naturals',75,95,'صحة',4.8,'خصم 21%'],
            ['مالتي فيتامين رياضي','Animal Pack',145,0,'فيتامينات',4.5,''],
        ];
        foreach ($prodData as $p) {
            Product::create(['name'=>$p[0],'brand'=>$p[1],'price'=>$p[2],'old_price'=>$p[3] ?: null,'category'=>$p[4],'rating'=>$p[5],'badge'=>$p[6] ?: null]);
        }

        // Conversations
        $trainerConv = Conversation::create(['trainer_id'=>$trainer->id, 'member_id'=>$members[0]->id, 'last_message_at'=>now()]);
        Message::create(['conversation_id'=>$trainerConv->id,'sender_id'=>$members[0]->id,'message'=>'مرحبا دكتور! شيلت 1.5 كغ هذا الأسبوع 🎉','type'=>'text']);
        Message::create(['conversation_id'=>$trainerConv->id,'sender_id'=>$trainer->id,'message'=>'ممتاز يا أحمد! استمر على نفس النظام الغذائي','type'=>'text']);
        Message::create(['conversation_id'=>$trainerConv->id,'sender_id'=>$members[0]->id,'message'=>'هل أزيد الوزن هذا الأسبوع ولا أكمل نفس الوزن؟','type'=>'text']);
        Message::create(['conversation_id'=>$trainerConv->id,'sender_id'=>$trainer->id,'message'=>'شوف الفيديو وإذا الشكل صح زد 5 كغ','type'=>'text']);

        $conv2 = Conversation::create(['trainer_id'=>$trainer->id, 'member_id'=>$members[1]->id, 'last_message_at'=>now()->subHours(2)]);
        Message::create(['conversation_id'=>$conv2->id,'sender_id'=>$trainer->id,'message'=>'نورا كيف حال النظام الغذائي الجديد؟','type'=>'text']);
        Message::create(['conversation_id'=>$conv2->id,'sender_id'=>$members[1]->id,'message'=>'صراحة أحسن بكثير! شكراً دكتور!','type'=>'text']);

        echo "✅ تم إنشاء البيانات بنجاح!\n";
        echo "🔑 Admin: admin@fitness.com / password\n";
        echo "🔑 Trainer: mohamed@fitness.com / password\n";
        echo "🔑 Members: ahmed@test.com, noura@test.com, faisal@test.com, reem@test.com / password\n";
    }
}
