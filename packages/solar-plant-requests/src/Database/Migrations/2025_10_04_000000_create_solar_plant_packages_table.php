<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول پکیج‌های ثابت
        Schema::create('solar_plant_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('capacity_kw');          // ظرفیت: 5 یا 10 کیلووات
            $table->string('title');                          // عنوان: مثلاً «۵ کیلووات - گزینه الف»
            $table->text('description')->nullable();          // توضیح کالاها و تجهیزات
            $table->unsignedBigInteger('price');              // قیمت (ریال)
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // فیلدهای انتخاب پکیج و پیمانکار به درخواست
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('solar_plant_requests', 'selected_package_id')) {
                $table->unsignedBigInteger('selected_package_id')->nullable()->after('contractor_name');
                $table->foreign('selected_package_id')->references('id')->on('solar_plant_packages')->nullOnDelete();
            }
            if (!Schema::hasColumn('solar_plant_requests', 'selected_package_price')) {
                $table->unsignedBigInteger('selected_package_price')->nullable()->after('selected_package_id');
            }
            if (!Schema::hasColumn('solar_plant_requests', 'selected_package_title')) {
                $table->string('selected_package_title')->nullable()->after('selected_package_price');
            }
        });

        // درج پکیج‌های ثابت
        $now = now();
        $packages = [
            // ──────────── ۵ کیلووات ────────────
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه الف (اقتصادی)',
                'description' => 'پنل خورشیدی درجه ۲، اینورتر استرینگ داخلی، بدون باتری، نصب بر روی سازه فلزی ساده',
                'price'       => 1200000000,
                'sort_order'  => 1,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه ب (استاندارد)',
                'description' => 'پنل خورشیدی درجه ۱، اینورتر استرینگ خارجی، بدون باتری، نصب بر روی سازه آلومینیومی',
                'price'       => 1600000000,
                'sort_order'  => 2,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه ج (پرمیوم)',
                'description' => 'پنل خورشیدی هاف‌سل درجه ۱، اینورتر هیبرید، باتری لیتیوم ۵ کیلووات‌ساعت، نصب با سازه آلومینیومی پرمیوم',
                'price'       => 2400000000,
                'sort_order'  => 3,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // ──────────── ۱۰ کیلووات ────────────
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه الف (اقتصادی)',
                'description' => 'پنل خورشیدی درجه ۲، دو اینورتر استرینگ داخلی، بدون باتری، نصب بر روی سازه فلزی ساده',
                'price'       => 2200000000,
                'sort_order'  => 4,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه ب (استاندارد)',
                'description' => 'پنل خورشیدی درجه ۱، اینورتر سه‌فاز خارجی، بدون باتری، نصب بر روی سازه آلومینیومی',
                'price'       => 3000000000,
                'sort_order'  => 5,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه ج (پرمیوم)',
                'description' => 'پنل خورشیدی هاف‌سل درجه ۱، اینورتر هیبرید سه‌فاز، باتری لیتیوم ۱۰ کیلووات‌ساعت، نصب با سازه آلومینیومی پرمیوم',
                'price'       => 4500000000,
                'sort_order'  => 6,
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($packages as $package) {
            \DB::table('solar_plant_packages')->insertOrIgnore($package);
        }
    }

    public function down(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->dropForeign(['selected_package_id']);
            $table->dropColumn(['selected_package_id', 'selected_package_price', 'selected_package_title']);
        });

        Schema::dropIfExists('solar_plant_packages');
    }
};
