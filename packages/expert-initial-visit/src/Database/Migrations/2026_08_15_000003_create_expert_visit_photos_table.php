<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_visit_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expert_initial_visit_id')
                  ->constrained('expert_initial_visits')
                  ->onDelete('cascade')
                  ->comment('شناسه فرم بازدید');

            $table->enum('photo_type', [
                'general_view',        // عکس فضای کلی محل
                'panel_location',      // محل پیشنهادی نصب پنل
                'electrical_panel',    // تابلو برق
                'inverter_location',   // محل پیشنهادی نصب اینورتر
                'battery_location',    // محل پیشنهادی نصب باتری
                'structure',           // وضعیت سازه
                'obstacle',            // موانع
                'shading_source',      // منابع سایه‌اندازی
                'cable_route',         // مسیر کابل‌کشی
                'other',               // سایر
            ])->comment('نوع تصویر');

            $table->string('path')->comment('مسیر فایل');
            $table->string('caption')->nullable()->comment('توضیح کوتاه');
            $table->unsignedSmallInteger('sort_order')->default(0)->comment('ترتیب نمایش');

            $table->timestamps();

            $table->index('expert_initial_visit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_visit_photos');
    }
};
