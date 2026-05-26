<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrition_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_plan_id')->constrained()->cascadeOnDelete();
            $table->string('meal_type');
            $table->string('time')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('calories')->default(0);
            $table->decimal('protein', 5, 2)->default(0);
            $table->decimal('carbs', 5, 2)->default(0);
            $table->decimal('fat', 5, 2)->default(0);
            $table->json('items')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_meals');
    }
};