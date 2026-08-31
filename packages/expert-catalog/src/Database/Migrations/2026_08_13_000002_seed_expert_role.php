<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('behin_roles')->where('name', 'کارشناس')->exists();
        if (! $exists) {
            DB::table('behin_roles')->insert([
                'name'       => 'کارشناس',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('behin_roles')->where('name', 'کارشناس')->delete();
    }
};
