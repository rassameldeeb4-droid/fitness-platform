<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->decimal('wrist_circumference', 5, 2)->nullable()->after('target_weight');
            $table->decimal('waist_circumference', 5, 2)->nullable()->after('wrist_circumference');
            $table->string('job')->nullable()->after('complaints');
        });
    }

    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropColumn(['wrist_circumference', 'waist_circumference', 'job']);
        });
    }
};
