<?php

namespace BehinCrmContractors\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractorRoleSeeder extends Seeder
{
    /**
     * Seed the 'پیمانکار' role into behin_roles.
     * Uses firstOrCreate semantics to avoid duplicate entries.
     * Requirement: 8.1, 8.2
     */
    public function run(): void
    {
        $exists = DB::table('behin_roles')->where('name', 'پیمانکار')->exists();

        if (! $exists) {
            DB::table('behin_roles')->insert([
                'name'       => 'پیمانکار',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
