<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_visit_equipment_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expert_initial_visit_id')
                  ->constrained('expert_initial_visits')
                  ->onDelete('cascade')
                  ->comment('شناسه فرم بازدید');

            $table->string('name')->comment('نام تجهیز');
            $table->unsignedSmallInteger('quantity')->default(1)->comment('تعداد');
            $table->decimal('power_watts', 8, 2)->nullable()->comment('توان هر تجهیز (W)');
            $table->decimal('total_power_watts', 8, 2)->nullable()->comment('توان کل (W)');
            $table->unsignedSmallInteger('usage_hours')->nullable()->comment('مدت استفاده (ساعت)');
            $table->boolean('is_critical')->default(false)->comment('ضروری است؟');
            $table->string('notes')->nullable()->comment('توضیحات');

            $table->timestamps();

            $table->index('expert_initial_visit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_visit_equipment_items');
    }
};
