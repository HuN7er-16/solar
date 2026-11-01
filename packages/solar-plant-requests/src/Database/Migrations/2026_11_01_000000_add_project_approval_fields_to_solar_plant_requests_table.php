<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {

            // جزئیات پروژه که راهبر تعریف می‌کند (JSON)
            if (!Schema::hasColumn('solar_plant_requests', 'project_details')) {
                $table->json('project_details')
                      ->nullable()
                      ->after('contractor_name')
                      ->comment('جزئیات پروژه تعریف‌شده توسط راهبر');
            }

            // نتیجه تایید/رد پروژه توسط متقاضی
            if (!Schema::hasColumn('solar_plant_requests', 'project_approval_status')) {
                $table->enum('project_approval_status', ['approved', 'rejected'])
                      ->nullable()
                      ->after('project_details')
                      ->comment('نتیجه تایید پروژه توسط متقاضی');
            }

            // دلیل رد پروژه توسط متقاضی
            if (!Schema::hasColumn('solar_plant_requests', 'project_rejection_reason')) {
                $table->text('project_rejection_reason')
                      ->nullable()
                      ->after('project_approval_status')
                      ->comment('دلیل رد پروژه توسط متقاضی');
            }

            // تاریخ تایید/رد پروژه توسط متقاضی
            if (!Schema::hasColumn('solar_plant_requests', 'project_approved_at')) {
                $table->timestamp('project_approved_at')
                      ->nullable()
                      ->after('project_rejection_reason')
                      ->comment('زمان تایید یا رد پروژه توسط متقاضی');
            }
        });
    }

    public function down(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->dropColumn([
                'project_details',
                'project_approval_status',
                'project_rejection_reason',
                'project_approved_at',
            ]);
        });
    }
};
