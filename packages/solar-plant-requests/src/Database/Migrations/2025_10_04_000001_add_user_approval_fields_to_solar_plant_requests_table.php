<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            // نتیجه تایید/رد متقاضی از گزارش کارشناسی اولیه
            if (!Schema::hasColumn('solar_plant_requests', 'user_approval_status')) {
                $table->enum('user_approval_status', ['approved', 'rejected'])
                      ->nullable()
                      ->after('status')
                      ->comment('نتیجه تایید متقاضی از گزارش کارشناسی');
            }

            // دلیل رد توسط متقاضی
            if (!Schema::hasColumn('solar_plant_requests', 'user_rejection_reason')) {
                $table->text('user_rejection_reason')
                      ->nullable()
                      ->after('user_approval_status')
                      ->comment('دلیل رد گزارش کارشناسی توسط متقاضی');
            }

            // تاریخ تایید/رد متقاضی
            if (!Schema::hasColumn('solar_plant_requests', 'user_approved_at')) {
                $table->timestamp('user_approved_at')
                      ->nullable()
                      ->after('user_rejection_reason')
                      ->comment('زمان تایید یا رد متقاضی');
            }

            // شناسه پیمانکار از جدول contractors (نه users)
            if (!Schema::hasColumn('solar_plant_requests', 'selected_contractor_id')) {
                $table->unsignedBigInteger('selected_contractor_id')
                      ->nullable()
                      ->after('user_approved_at')
                      ->comment('شناسه پیمانکار انتخاب‌شده از جدول contractors');
                $table->foreign('selected_contractor_id')
                      ->references('id')
                      ->on('contractors')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->dropForeign(['selected_contractor_id']);
            $table->dropColumn([
                'user_approval_status',
                'user_rejection_reason',
                'user_approved_at',
                'selected_contractor_id',
            ]);
        });
    }
};
