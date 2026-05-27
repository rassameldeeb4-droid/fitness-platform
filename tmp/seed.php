<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "<pre>";

// ==================== MEMBERS ====================
echo "Adding members...\n";
$members = [
    ['name' => 'أحمد محمد', 'email' => 'ahmed@test.com', 'goal' => 'weight_loss', 'height' => 175, 'current_weight' => 90, 'target_weight' => 75, 'activity_level' => 'moderate', 'birth_date' => '1995-03-15'],
    ['name' => 'سارة علي', 'email' => 'sara@test.com', 'goal' => 'muscle_gain', 'height' => 165, 'current_weight' => 60, 'target_weight' => 68, 'activity_level' => 'active', 'birth_date' => '1998-07-22'],
    ['name' => 'خالد عمر', 'email' => 'khaled@test.com', 'goal' => 'general_fitness', 'height' => 180, 'current_weight' => 80, 'target_weight' => 78, 'activity_level' => 'moderate', 'birth_date' => '1992-11-10'],
    ['name' => 'نورة حسن', 'email' => 'noura@test.com', 'goal' => 'weight_loss', 'height' => 160, 'current_weight' => 75, 'target_weight' => 60, 'activity_level' => 'sedentary', 'birth_date' => '2000-05-08'],
    ['name' => 'فيصل عبدالله', 'email' => 'faisal@test.com', 'goal' => 'muscle_gain', 'height' => 185, 'current_weight' => 70, 'target_weight' => 85, 'activity_level' => 'very_active', 'birth_date' => '1990-01-30'],
];

foreach ($members as $m) {
    $existing = DB::table('users')->where('email', $m['email'])->first();
    if ($existing) { echo "  {$m['name']} already exists\n"; continue; }
    $userId = DB::table('users')->insertGetId([
        'name' => $m['name'],
        'email' => $m['email'],
        'password' => Hash::make('password'),
        'role' => 'member',
        'phone' => '05' . rand(10000000, 99999999),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days')),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    $bmi = round($m['current_weight'] / (($m['height']/100) * ($m['height']/100)), 1);
    DB::table('member_profiles')->insert([
        'user_id' => $userId,
        'goal' => $m['goal'],
        'height' => $m['height'],
        'current_weight' => $m['current_weight'],
        'target_weight' => $m['target_weight'],
        'activity_level' => $m['activity_level'],
        'birth_date' => $m['birth_date'],
        'bmi' => $bmi,
        'progress_percentage' => rand(0, 30),
        'workout_days_per_week' => rand(3, 6),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "  Added: {$m['name']}\n";
}

// ==================== PRODUCTS ====================
echo "\nAdding products...\n";
$products = [
    ['name' => 'واي بروتين - فانيليا', 'name_en' => 'Whey Protein Vanilla', 'brand' => 'Optimum Nutrition', 'category' => 'supplements', 'price' => 249, 'old_price' => 299, 'badge' => 'أفضل سعر', 'rating' => 4.8, 'review_count' => 124],
    ['name' => 'كرياتين مونوهيدرات', 'name_en' => 'Creatine Monohydrate', 'brand' => 'MyProtein', 'category' => 'supplements', 'price' => 189, 'old_price' => 229, 'badge' => null, 'rating' => 4.6, 'review_count' => 89],
    ['name' => 'بار بروتين - شوكولاتة', 'name_en' => 'Protein Bar Chocolate', 'brand' => 'Quest', 'category' => 'snacks', 'price' => 15, 'old_price' => null, 'badge' => 'جديد', 'rating' => 4.3, 'review_count' => 45],
    ['name' => 'قارورة ماء 1 لتر', 'name_en' => 'Water Bottle 1L', 'brand' => 'GymShark', 'category' => 'accessories', 'price' => 79, 'old_price' => 99, 'badge' => 'تخفيض', 'rating' => 4.5, 'review_count' => 67],
    ['name' => 'حقيبة رياضية كبيرة', 'name_en' => 'Large Gym Bag', 'brand' => 'Nike', 'category' => 'accessories', 'price' => 349, 'old_price' => null, 'badge' => null, 'rating' => 4.7, 'review_count' => 33],
    ['name' => 'شورت رياضي رجالي', 'name_en' => 'Men Sports Shorts', 'brand' => 'Adidas', 'category' => 'clothing', 'price' => 129, 'old_price' => 159, 'badge' => null, 'rating' => 4.2, 'review_count' => 56],
    ['name' => 'حزام رفع أثقال', 'name_en' => 'Weight Lifting Belt', 'brand' => 'Rogue', 'category' => 'accessories', 'price' => 199, 'old_price' => null, 'badge' => 'ممتاز', 'rating' => 4.9, 'review_count' => 28],
    ['name' => 'مقياس دهون ذكي', 'name_en' => 'Smart Body Scale', 'brand' => 'Xiaomi', 'category' => 'electronics', 'price' => 299, 'old_price' => 349, 'badge' => 'تخفيض', 'rating' => 4.4, 'review_count' => 112],
];

foreach ($products as $p) {
    DB::table('products')->insert([
        'name' => $p['name'],
        'name_en' => $p['name_en'],
        'brand' => $p['brand'],
        'category' => $p['category'],
        'price' => $p['price'],
        'old_price' => $p['old_price'],
        'rating' => $p['rating'],
        'review_count' => $p['review_count'],
        'badge' => $p['badge'],
        'is_active' => true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "  Added: {$p['name']}\n";
}

// ==================== EXERCISES ====================
echo "\nAdding exercises...\n";
$exercises = [
    ['name' => 'بنش برس (دمبلز)', 'name_en' => 'Dumbbell Bench Press', 'category' => 'chest', 'muscle_group' => 'صدر', 'sets_default' => 4, 'reps_default' => '10-12', 'difficulty' => 'intermediate', 'equipment' => 'دمبلز', 'calories_per_set' => 12],
    ['name' => 'سكوات', 'name_en' => 'Squat', 'category' => 'legs', 'muscle_group' => 'أرجل', 'sets_default' => 4, 'reps_default' => '10-12', 'difficulty' => 'beginner', 'equipment' => 'بار', 'calories_per_set' => 15],
    ['name' => 'سحب علوي (Pull Up)', 'name_en' => 'Pull Up', 'category' => 'back', 'muscle_group' => 'ظهر', 'sets_default' => 3, 'reps_default' => '8-10', 'difficulty' => 'advanced', 'equipment' => 'بار سحب', 'calories_per_set' => 10],
    ['name' => 'ضغط كتف (دمبلز)', 'name_en' => 'Shoulder Press', 'category' => 'shoulders', 'muscle_group' => 'كتف', 'sets_default' => 3, 'reps_default' => '12', 'difficulty' => 'intermediate', 'equipment' => 'دمبلز', 'calories_per_set' => 11],
    ['name' => 'تمرين بايسبس (دمبلز)', 'name_en' => 'Dumbbell Bicep Curl', 'category' => 'arms', 'muscle_group' => 'بايسبس', 'sets_default' => 3, 'reps_default' => '12-15', 'difficulty' => 'beginner', 'equipment' => 'دمبلز', 'calories_per_set' => 8],
    ['name' => 'رفع أرجل معلق', 'name_en' => 'Hanging Leg Raise', 'category' => 'core', 'muscle_group' => 'بطن', 'sets_default' => 3, 'reps_default' => '15', 'difficulty' => 'intermediate', 'equipment' => 'بار سحب', 'calories_per_set' => 7],
    ['name' => 'Deadlift (رفع ميت)', 'name_en' => 'Deadlift', 'category' => 'back', 'muscle_group' => 'ظهر كامل', 'sets_default' => 4, 'reps_default' => '8', 'difficulty' => 'advanced', 'equipment' => 'بار', 'calories_per_set' => 18],
    ['name' => 'تمارين كارديو (جهاز مشي)', 'name_en' => 'Treadmill', 'category' => 'cardio', 'muscle_group' => 'كارديو', 'sets_default' => 1, 'reps_default' => '30 دقيقة', 'difficulty' => 'beginner', 'equipment' => 'جهاز مشي', 'calories_per_set' => 0],
    ['name' => 'ضغط ترايسبس (كابل)', 'name_en' => 'Tricep Pushdown', 'category' => 'arms', 'muscle_group' => 'ترايسبس', 'sets_default' => 3, 'reps_default' => '12-15', 'difficulty' => 'beginner', 'equipment' => 'كابل', 'calories_per_set' => 9],
    ['name' => 'صف دمبل', 'name_en' => 'Dumbbell Row', 'category' => 'back', 'muscle_group' => 'ظهر', 'sets_default' => 3, 'reps_default' => '10-12', 'difficulty' => 'intermediate', 'equipment' => 'دمبلز', 'calories_per_set' => 11],
];

foreach ($exercises as $e) {
    DB::table('exercises')->insert([
        'name' => $e['name'],
        'name_en' => $e['name_en'],
        'category' => $e['category'],
        'muscle_group' => $e['muscle_group'],
        'sets_default' => $e['sets_default'],
        'reps_default' => $e['reps_default'],
        'difficulty' => $e['difficulty'],
        'equipment' => $e['equipment'],
        'calories_per_set' => $e['calories_per_set'],
        'is_active' => true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "  Added: {$e['name']}\n";
}

echo "\nDone! All data seeded.</pre>";
