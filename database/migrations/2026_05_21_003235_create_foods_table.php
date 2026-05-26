<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('category')->default('other');
            $table->integer('calories_per_100g');
            $table->decimal('protein_per_100g', 5, 2)->default(0);
            $table->decimal('carbs_per_100g', 5, 2)->default(0);
            $table->decimal('fat_per_100g', 5, 2)->default(0);
            $table->decimal('fiber_per_100g', 5, 2)->default(0);
            $table->string('vitamins')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};