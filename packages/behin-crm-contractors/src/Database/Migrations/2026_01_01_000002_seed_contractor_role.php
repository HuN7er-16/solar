<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Seeds the 'پیمانکار' role into behin_roles if it does not already exist.
     */
    public function up(): void
    {
        if (!DB::table('behin_roles')->where('name', 'پیمانکار')->exists()) {
            DB::table('behin_roles')->insert([
                'name' => 'پیمانکار',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     * Removes the 'پیمانکار' role from behin_roles.
     */
    public function down(): void
    {
        DB::table('behin_roles')->where('name', 'پیمانکار')->delete();
    }
};
