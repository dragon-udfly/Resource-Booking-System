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
            $table->string('reason_of_rejection')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hall_booking', function (Blueprint $table) {
            $table->dropColumn('reason_of_rejection');
        });
    }
};
