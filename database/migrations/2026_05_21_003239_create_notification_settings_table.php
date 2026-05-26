<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('workout_reminder')->default(true);
            $table->integer('workout_reminder_minutes')->default(30);
            $table->boolean('meal_reminder')->default(true);
            $table->integer('meal_reminder_minutes')->default(15);
            $table->boolean('water_reminder')->default(true);
            $table->integer('water_interval_minutes')->default(120);
            $table->boolean('sleep_reminder')->default(false);
            $table->string('sleep_time')->default('22:30');
            $table->boolean('measurement_reminder')->default(true);
            $table->string('measurement_day')->default('saturday');
            $table->boolean('supplement_reminder')->default(false);
            $table->boolean('whatsapp_enabled')->default(false);
            $table->string('whatsapp_phone')->nullable();
            $table->string('whatsapp_api_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};