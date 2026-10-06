<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── ۱. اضافه کردن user_id به جدول contractors ───────────────────────
        Schema::table('contractors', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                  ->unique()
                  ->nullable()
                  ->comment('شناسه کاربر پیمانکار')
                  ->after('id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });

        // ─── ۲. seed نقش «پیمانکار» با id=2 در جدول behin_roles ──────────────
        $exists = DB::table('behin_roles')->where('id', 2)->exists();
        if (! $exists) {
            DB::table('behin_roles')->insert([
                'id'         => 2,
                'name'       => 'پیمانکار',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // اگر رکورد با id=2 وجود دارد ولی نام آن «پیمانکار» نیست، نام را به‌روز کن
            DB::table('behin_roles')
                ->where('id', 2)
                ->whereNot('name', 'پیمانکار')
                ->update(['name' => 'پیمانکار', 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
