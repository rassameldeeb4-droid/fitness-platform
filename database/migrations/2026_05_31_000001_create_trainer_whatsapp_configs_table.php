<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainer_whatsapp_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainer_id')->unique();
            $table->string('server_url', 255)->nullable();
            $table->string('api_key', 100)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->boolean('is_connected')->default(false);
            $table->boolean('notify_nutrition')->default(true);
            $table->boolean('notify_workout')->default(true);
            $table->boolean('notify_progress')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_whatsapp_configs');
    }
};
