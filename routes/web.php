<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Admin\GymController as AdminGymController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\RevenueController as AdminRevenueController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;
use App\Http\Controllers\Trainer\TraineeController as TrainerTraineeController;
use App\Http\Controllers\Trainer\NutritionPlanController as TrainerNutritionPlanController;
use App\Http\Controllers\Trainer\WorkoutPlanController as TrainerWorkoutPlanController;
use App\Http\Controllers\Trainer\ProgressController as TrainerProgressController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\NutritionPlanController as DoctorNutritionPlanController;
use App\Http\Controllers\Doctor\WorkoutPlanController as DoctorWorkoutPlanController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\NutritionController as MemberNutritionController;
use App\Http\Controllers\Member\WorkoutController as MemberWorkoutController;
use App\Http\Controllers\Member\ProgressController as MemberProgressController;
use App\Http\Controllers\Member\NotificationController as MemberNotificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AiController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'super_admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'trainer') return redirect()->route('trainer.dashboard');
        if ($user->role === 'doctor') return redirect()->route('doctor.dashboard');
        return redirect()->route('member.dashboard');
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/members', [AdminMemberController::class, 'index'])->name('members');
        Route::get('/members/create', [AdminMemberController::class, 'create'])->name('members.create');
        Route::post('/members', [AdminMemberController::class, 'store'])->name('members.store');
        Route::get('/members/{id}', [AdminMemberController::class, 'show'])->name('members.show');
        Route::get('/trainers', [AdminTrainerController::class, 'index'])->name('trainers');
        Route::get('/trainers/{id}', [AdminTrainerController::class, 'show'])->name('trainers.show');
        Route::get('/gyms', [AdminGymController::class, 'index'])->name('gyms');
        Route::post('/gyms', [AdminGymController::class, 'store'])->name('gyms.store');
        Route::get('/packages', [AdminPackageController::class, 'index'])->name('packages');
        Route::post('/packages', [AdminPackageController::class, 'store'])->name('packages.store');
        Route::get('/revenue', [AdminRevenueController::class, 'index'])->name('revenue');
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        // Super Admin / Store Management
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/exercises', [AdminExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/exercises/create', [AdminExerciseController::class, 'create'])->name('exercises.create');
        Route::post('/exercises', [AdminExerciseController::class, 'store'])->name('exercises.store');
        Route::get('/exercises/{exercise}/edit', [AdminExerciseController::class, 'edit'])->name('exercises.edit');
        Route::put('/exercises/{exercise}', [AdminExerciseController::class, 'update'])->name('exercises.update');
        Route::delete('/exercises/{exercise}', [AdminExerciseController::class, 'destroy'])->name('exercises.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

        Route::get('/doctors', [AdminDoctorController::class, 'index'])->name('doctors');
    });

    // Trainer Routes
    Route::middleware(['role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/trainees', [TrainerTraineeController::class, 'index'])->name('trainees');
        Route::get('/trainees/create', [TrainerTraineeController::class, 'create'])->name('trainees.create');
        Route::post('/trainees', [TrainerTraineeController::class, 'store'])->name('trainees.store');
        Route::get('/trainees/{id}', [TrainerTraineeController::class, 'show'])->name('trainees.show');
        Route::get('/nutrition/create/{memberId}', [TrainerNutritionPlanController::class, 'create'])->name('nutrition.create');
        Route::post('/nutrition', [TrainerNutritionPlanController::class, 'store'])->name('nutrition.store');
        Route::get('/nutrition/{id}', [TrainerNutritionPlanController::class, 'show'])->name('nutrition.show');
        Route::get('/workout/create/{memberId}', [TrainerWorkoutPlanController::class, 'create'])->name('workout.create');
        Route::post('/workout', [TrainerWorkoutPlanController::class, 'store'])->name('workout.store');
        Route::get('/workout/{id}', [TrainerWorkoutPlanController::class, 'show'])->name('workout.show');
        Route::get('/progress', [TrainerProgressController::class, 'index'])->name('progress');
        Route::get('/progress/{memberId}', [TrainerProgressController::class, 'show'])->name('progress.show');
    });

    // Doctor Routes
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/patients', [DoctorPatientController::class, 'index'])->name('patients');
        Route::get('/patients/create', [DoctorPatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [DoctorPatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{id}', [DoctorPatientController::class, 'show'])->name('patients.show');
        Route::get('/nutrition/create/{memberId}', [DoctorNutritionPlanController::class, 'create'])->name('nutrition.create');
        Route::post('/nutrition', [DoctorNutritionPlanController::class, 'store'])->name('nutrition.store');
        Route::get('/nutrition/{id}', [DoctorNutritionPlanController::class, 'show'])->name('nutrition.show');
        Route::get('/workout/create/{memberId}', [DoctorWorkoutPlanController::class, 'create'])->name('workout.create');
        Route::post('/workout', [DoctorWorkoutPlanController::class, 'store'])->name('workout.store');
        Route::get('/workout/{id}', [DoctorWorkoutPlanController::class, 'show'])->name('workout.show');
    });

    // Member Routes
    Route::middleware(['role:member'])->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/nutrition', [MemberNutritionController::class, 'index'])->name('nutrition');
        Route::get('/food-analyzer', [MemberNutritionController::class, 'analyzeFood'])->name('food-analyzer');
        Route::post('/food-analyze', [MemberNutritionController::class, 'analyzeFoodPost'])->name('food-analyze');
        Route::get('/workouts', [MemberWorkoutController::class, 'index'])->name('workouts');
        Route::post('/workouts/mark-exercise', [MemberWorkoutController::class, 'markExerciseDone'])->name('workouts.mark-exercise');
        Route::get('/progress', [MemberProgressController::class, 'index'])->name('progress');
        Route::post('/progress', [MemberProgressController::class, 'store'])->name('progress.store');
        Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications', [MemberNotificationController::class, 'update'])->name('notifications.update');
    });

    // Shared Routes (accessible by any authenticated user)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/send-exercise', [ChatController::class, 'sendExercise'])->name('chat.send-exercise');

    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises');
    Route::get('/exercises/{id}', [ExerciseController::class, 'show'])->name('exercises.show');

    Route::get('/foods', [FoodController::class, 'index'])->name('foods');
    Route::get('/foods/analyze', [FoodController::class, 'analyze'])->name('foods.analyze');
    Route::post('/foods/analyze', [FoodController::class, 'analyzePost'])->name('foods.analyze-post');

    Route::get('/store', [ProductController::class, 'index'])->name('store');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/my-orders', [CheckoutController::class, 'orders'])->name('store.orders');

    Route::get('/gyms', [GymController::class, 'index'])->name('gyms');
    Route::post('/gyms/rate', [GymController::class, 'rate'])->name('gyms.rate');

    Route::get('/profile/trainer/{id}', [ProfileController::class, 'showTrainer'])->name('profile.trainer');
    Route::get('/profile/member/{id}', [ProfileController::class, 'showMember'])->name('profile.member');

    // AI Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('/generate-nutrition', [App\Http\Controllers\AiController::class, 'generateNutrition'])->name('generate-nutrition');
        Route::post('/generate-workout', [App\Http\Controllers\AiController::class, 'generateWorkout'])->name('generate-workout');
        Route::post('/analyze-food', [App\Http\Controllers\AiController::class, 'analyzeFood'])->name('analyze-food');
    });
});

require __DIR__.'/auth.php';
