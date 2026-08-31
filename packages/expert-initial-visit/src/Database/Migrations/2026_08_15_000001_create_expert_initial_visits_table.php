<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_initial_visits', function (Blueprint $table) {
            $table->id();

            // ارتباط با تقاضا
            $table->foreignId('solar_plant_request_id')
                  ->constrained('solar_plant_requests')
                  ->onDelete('cascade')
                  ->comment('شناسه تقاضا');

            // ارتباط با کارشناس
            $table->foreignId('expert_user_id')
                  ->constrained('users')
                  ->comment('شناسه کارشناس');

            // ======= بخش ۱: اطلاعات بازدید =======
            $table->date('visit_date')->comment('تاریخ بازدید');

            // ======= بخش ۲: احراز محل =======
            $table->boolean('location_matches')->default(false)->comment('محل با اعلام متقاضی مطابقت دارد؟');
            $table->text('actual_address')->nullable()->comment('آدرس واقعی در صورت عدم مطابقت');
            $table->boolean('location_physically_confirmed')->default(false)->comment('وجود فیزیکی محل تأیید شد؟');
            $table->enum('location_access', ['easy', 'medium', 'hard'])->nullable()->comment('دسترسی به محل');

            // ======= بخش ۳: وضعیت کلی محل =======
            $table->boolean('suitable_space_exists')->default(false)->comment('فضای مناسب برای احداث وجود دارد؟');
            $table->enum('installation_location_type', [
                'flat_roof', 'sloped_roof', 'ground', 'parking_canopy', 'other'
            ])->nullable()->comment('نوع محل نصب');
            $table->string('installation_location_type_other')->nullable()->comment('توضیح نوع محل در صورت سایر');
            $table->unsignedInteger('total_area_sqm')->nullable()->comment('مساحت کل محل (مترمربع)');
            $table->unsignedInteger('usable_area_sqm')->nullable()->comment('مساحت قابل استفاده (مترمربع)');
            $table->boolean('access_to_installation_site')->default(false)->comment('دسترسی به محل نصب مناسب است؟');
            $table->boolean('physical_obstacle_exists')->default(false)->comment('مانع فیزیکی وجود دارد؟');
            $table->json('obstacle_types')->nullable()->comment('انواع موانع');
            $table->text('obstacle_notes')->nullable()->comment('توضیح موانع');

            // ======= بخش ۴: سطح نصب پنل =======
            $table->enum('surface_type', ['concrete', 'metal', 'tile', 'soil', 'other'])->nullable()->comment('نوع سطح نصب');
            $table->enum('surface_orientation', ['horizontal', 'sloped'])->nullable()->comment('وضعیت سطح');
            $table->enum('panel_direction', [
                'south', 'southeast', 'southwest', 'east', 'west', 'north', 'other'
            ])->nullable()->comment('جهت نصب پنل');
            $table->enum('shading_level', ['none', 'low', 'medium', 'high'])->nullable()->comment('میزان سایه‌اندازی');
            $table->json('shading_sources')->nullable()->comment('منابع سایه‌اندازی');
            $table->enum('surface_condition', ['suitable', 'unsuitable', 'suitable_with_fix'])->nullable()->comment('وضعیت سطح برای نصب');
            $table->text('surface_notes')->nullable()->comment('توضیحات سطح');

            // ======= بخش ۵: سازه و ایمنی =======
            $table->enum('structure_load_capacity', [
                'suitable', 'needs_reinforcement', 'unsuitable', 'needs_expert_review'
            ])->nullable()->comment('وضعیت تحمل بار سازه');
            $table->boolean('reinforcement_needed')->default(false)->comment('نیاز به مقاوم‌سازی');
            $table->boolean('special_structure_needed')->default(false)->comment('نیاز به سازه خاص');
            $table->json('site_risks')->nullable()->comment('ریسک‌های محل');
            $table->enum('overall_risk_level', ['low', 'medium', 'high'])->nullable()->comment('سطح ریسک کلی');
            $table->text('structure_notes')->nullable()->comment('توضیحات سازه‌ای');

            // ======= بخش ۶: برق و زیرساخت =======
            $table->enum('electricity_type', ['single_phase', 'three_phase'])->nullable()->comment('نوع برق');
            $table->unsignedInteger('connection_capacity_ampere')->nullable()->comment('ظرفیت انشعاب (آمپر)');
            $table->boolean('main_panel_accessible')->default(false)->comment('تابلو برق اصلی قابل دسترسی');
            $table->enum('main_panel_condition', [
                'suitable', 'needs_fix', 'unsuitable', 'needs_review'
            ])->nullable()->comment('وضعیت تابلو برق');
            $table->enum('electrical_installation_condition', [
                'suitable', 'needs_fix', 'unsuitable'
            ])->nullable()->comment('وضعیت کلی تأسیسات برق');
            $table->boolean('grid_connection_possible')->default(false)->comment('امکان اتصال به شبکه');
            $table->boolean('electrical_fix_needed')->default(false)->comment('نیاز به اصلاح تأسیسات برق');
            $table->text('electrical_notes')->nullable()->comment('توضیحات برق');

            // ======= بخش ۷: جمع‌بندی بار اضطراری =======
            $table->boolean('has_emergency_load')->default(false)->comment('نیاز به بار اضطراری دارد؟');
            $table->decimal('total_emergency_load_kw', 8, 2)->nullable()->comment('مجموع بار اضطراری (kW)');
            $table->unsignedInteger('emergency_supply_hours')->nullable()->comment('مدت زمان موردنیاز تأمین برق (ساعت)');
            $table->enum('battery_need', ['yes', 'no', 'optional'])->nullable()->comment('نیاز به باتری');
            $table->text('emergency_load_notes')->nullable()->comment('توضیحات بار اضطراری');

            // ======= بخش ۸: محل نصب تجهیزات =======
            $table->enum('inverter_location', ['yes', 'no', 'with_fix'])->nullable()->comment('محل مناسب اینورتر');
            $table->enum('battery_location', ['yes', 'no', 'with_fix', 'not_needed'])->nullable()->comment('محل مناسب باتری');
            $table->boolean('equipment_ventilation_ok')->default(false)->comment('تهویه محل تجهیزات مناسب');
            $table->boolean('cable_route_ok')->default(false)->comment('مسیر کابل‌کشی مناسب');
            $table->boolean('new_equipment_space_needed')->default(false)->comment('نیاز به ایجاد فضای جدید');
            $table->text('equipment_location_notes')->nullable()->comment('توضیحات محل تجهیزات');

            // ======= بخش ۹: ظرفیت پیشنهادی =======
            $table->decimal('applicant_requested_capacity_kw', 8, 2)->nullable()->comment('ظرفیت درخواست متقاضی (kW)');
            $table->decimal('installable_capacity_kw', 8, 2)->nullable()->comment('ظرفیت قابل نصب بر اساس فضا (kW)');
            $table->decimal('expert_proposed_capacity_kw', 8, 2)->nullable()->comment('ظرفیت پیشنهادی کارشناس (kW)');
            $table->decimal('expert_proposed_inverter_kw', 8, 2)->nullable()->comment('ظرفیت پیشنهادی اینورتر (kW)');
            $table->boolean('battery_required')->default(false)->comment('نیاز به باتری');
            $table->decimal('expert_proposed_battery_kwh', 8, 2)->nullable()->comment('ظرفیت پیشنهادی باتری (kWh)');
            $table->text('capacity_difference_reason')->nullable()->comment('علت تفاوت ظرفیت');

            // ======= بخش ۱۰: اصلاحات پیش از اجرا =======
            $table->boolean('pre_execution_fix_needed')->default(false)->comment('نیاز به اصلاح قبل از اجرا');
            $table->json('pre_execution_fix_types')->nullable()->comment('انواع اصلاحات');
            $table->text('pre_execution_fix_description')->nullable()->comment('شرح اصلاحات');

            // ======= بخش ۱۱: نتیجه ارزیابی =======
            $table->enum('assessment_result', [
                'feasible',           // قابل اجرا
                'feasible_with_fix',  // قابل اجرا با اصلاح
                'not_feasible',       // عدم امکان اجرا
            ])->comment('نتیجه ارزیابی اولیه');
            $table->text('not_feasible_reason')->nullable()->comment('علت عدم امکان اجرا (الزامی در صورت رد)');

            // ======= بخش ۱۲: جمع‌بندی =======
            $table->text('expert_summary')->nullable()->comment('جمع‌بندی و نظر کارشناسی');

            // ======= بخش ۱۴: وضعیت گزارش =======
            $table->enum('report_status', ['submitted'])->default('submitted')->comment('وضعیت گزارش');
            $table->timestamp('submitted_at')->nullable()->comment('تاریخ و ساعت ارسال');

            $table->timestamps();

            $table->index('solar_plant_request_id');
            $table->index('expert_user_id');
            $table->index('assessment_result');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_initial_visits');
    }
};
