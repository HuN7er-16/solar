<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unique()->comment('شناسه کاربر کارشناس');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('expert_code')->unique()->comment('کد کارشناس');
            $table->string('first_name')->comment('نام');
            $table->string('last_name')->comment('نام خانوادگی');
            $table->string('national_id', 10)->unique()->comment('کد ملی');

            $table->string('mobile', 11)->comment('شماره همراه');
            $table->string('phone', 11)->nullable()->comment('تلفن ثابت');

            $table->string('province')->comment('استان محل فعالیت');
            $table->string('city')->comment('شهر محل فعالیت');
            $table->text('address')->comment('آدرس');

            $table->boolean('is_certificated')->default(false)->comment('دارای گواهی صلاحیت حرفه‌ای');

            $table->timestamps();

            $table->index('province');
            $table->index('is_certificated');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experts');
    }
};
