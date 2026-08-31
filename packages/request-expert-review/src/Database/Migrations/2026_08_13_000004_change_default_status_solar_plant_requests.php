<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تغییر default وضعیت به initial_registration
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->string('status', 64)
                  ->default('initial_registration')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('solar_plant_requests', function (Blueprint $table) {
            $table->string('status', 64)
                  ->default('under_review')
                  ->change();
        });
    }
};
