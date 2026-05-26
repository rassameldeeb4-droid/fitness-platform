<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_days');
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('monthly');
            $table->integer('max_bookings')->default(0);
            $table->boolean('has_trainer')->default(false);
            $table->boolean('has_nutrition')->default(false);
            $table->boolean('has_ai')->default(false);
            $table->text('badge')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};