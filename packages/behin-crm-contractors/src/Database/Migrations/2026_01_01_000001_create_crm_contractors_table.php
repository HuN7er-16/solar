<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('crm_service_center_id')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('center_name');
            $table->string('mobile', 20);
            $table->string('province')->nullable()->index();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_contractors');
    }
};
