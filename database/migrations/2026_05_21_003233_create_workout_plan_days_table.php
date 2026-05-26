<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_plan_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
            $table->string('day_name');
            $table->string('focus');
            $table->integer('order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('exercise_workout_plan_day', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('sets')->default(3);
            $table->string('reps')->default('12');
            $table->string('rest_seconds')->default('60');
            $table->integer('order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_workout_plan_day');
        Schema::dropIfExists('workout_plan_days');
    }
};