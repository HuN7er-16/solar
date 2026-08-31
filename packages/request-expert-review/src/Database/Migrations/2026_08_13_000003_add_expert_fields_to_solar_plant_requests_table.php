<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            // شناسه کارشناس اختصاص‌یافته
            if (! Schema::hasColumn('solar_plant_requests', 'expert_user_id')) {
                $table->foreignId('expert_user_id')
                      ->nullable()
                      ->after('inspector_name')
                      ->constrained('users')
                      ->nullOnDelete()
                      ->comment('شناسه کارشناس اختصاص‌یافته به تقاضا');
            }

            // نام کارشناس (ذخیره دنامیک برای گزارش)
            if (! Schema::hasColumn('solar_plant_requests', 'expert_name')) {
                $table->string('expert_name')
                      ->nullable()
                      ->after('expert_user_id')
                      ->comment('نام کارشناس اختصاص‌یافته');
            }
        });
    }

    public function down(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->dropForeign(['expert_user_id']);
            $table->dropColumn(['expert_user_id', 'expert_name']);
        });
    }
};
