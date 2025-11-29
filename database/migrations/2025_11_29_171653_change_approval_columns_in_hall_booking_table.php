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
        Schema::table('hall_booking', function (Blueprint $table) {
            $table->string('administrative_officer_approved', 50)->default('pending')->change();
            $table->string('additional_government_agent_approved', 50)->default('pending')->change();
            $table->string('government_agent_approved', 50)->default('pending')->change();
            $table->string('final_approval', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hall_booking', function (Blueprint $table) {
            $table->boolean('administrative_officer_approved')->default(false)->change();
            $table->boolean('additional_government_agent_approved')->default(false)->change();
            $table->boolean('government_agent_approved')->default(false)->change();
            $table->boolean('final_approval')->default(false)->change();
        });
    }
};
