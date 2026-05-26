<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->string('image')->nullable()->after('gym_id');
            $table->text('injuries')->nullable()->after('workout_days_per_week');
            $table->text('complaints')->nullable()->after('injuries');
        });
    }

    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropColumn(['image', 'injuries', 'complaints']);
        });
    }
};
