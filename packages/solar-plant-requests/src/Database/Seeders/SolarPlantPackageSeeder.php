<?php

namespace SolarPlantRequests\Database\Seeders;

use Illuminate\Database\Seeder;
use SolarPlantRequests\Models\SolarPlantPackage;

class SolarPlantPackageSeeder extends Seeder
{
    /**
     * پکیج‌های ثابت نیروگاه خورشیدی
     *
     * هر ظرفیت (۵ و ۱۰ کیلووات) سه گزینه با تجهیزات متفاوت دارد:
     *   گزینه الف — تجهیزات پایه (اقتصادی)
     *   گزینه ب  — تجهیزات استاندارد
     *   گزینه ج  — تجهیزات پرمیوم
     */
    public function run(): void
    {
        $packages = [
            // ──────────── ۵ کیلووات ────────────
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه الف (اقتصادی)',
                'description' => 'پنل خورشیدی درجه ۲، اینورتر استرینگ داخلی، بدون باتری، نصب بر روی سازه فلزی ساده',
                'price'       => 1_200_000_000,
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه ب (استاندارد)',
                'description' => 'پنل خورشیدی درجه ۱، اینورتر استرینگ خارجی، بدون باتری، نصب بر روی سازه آلومینیومی',
                'price'       => 1_600_000_000,
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'capacity_kw' => 5,
                'title'       => '۵ کیلووات - گزینه ج (پرمیوم)',
                'description' => 'پنل خورشیدی هاف‌سل درجه ۱، اینورتر هیبرید، باتری لیتیوم ۵ کیلووات‌ساعت، نصب با سازه آلومینیومی پرمیوم',
                'price'       => 2_400_000_000,
                'sort_order'  => 3,
                'is_active'   => true,
            ],

            // ──────────── ۱۰ کیلووات ────────────
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه الف (اقتصادی)',
                'description' => 'پنل خورشیدی درجه ۲، دو اینورتر استرینگ داخلی، بدون باتری، نصب بر روی سازه فلزی ساده',
                'price'       => 2_200_000_000,
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه ب (استاندارد)',
                'description' => 'پنل خورشیدی درجه ۱، اینورتر سه‌فاز خارجی، بدون باتری، نصب بر روی سازه آلومینیومی',
                'price'       => 3_000_000_000,
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'capacity_kw' => 10,
                'title'       => '۱۰ کیلووات - گزینه ج (پرمیوم)',
                'description' => 'پنل خورشیدی هاف‌سل درجه ۱، اینورتر هیبرید سه‌فاز، باتری لیتیوم ۱۰ کیلووات‌ساعت، نصب با سازه آلومینیومی پرمیوم',
                'price'       => 4_500_000_000,
                'sort_order'  => 6,
                'is_active'   => true,
            ],
        ];

        foreach ($packages as $package) {
            SolarPlantPackage::firstOrCreate(
                ['title' => $package['title']],
                $package
            );
        }
    }
}
