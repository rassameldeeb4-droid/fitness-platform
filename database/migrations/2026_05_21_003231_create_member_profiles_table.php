<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('gym_id')->nullable()->constrained()->nullOnDelete();
            $table->string('goal')->default('weight_loss');
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('current_weight', 5, 2)->nullable();
            $table->decimal('target_weight', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->decimal('muscle_mass', 5, 2)->nullable();
            $table->string('activity_level')->default('moderate');
            $table->date('birth_date')->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->integer('workout_days_per_week')->default(4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};