<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            $table->dropColumn(['total_mark', 'date_calculated', 'f_years_since_application_created']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            $table->integer('total_mark')->nullable();
            $table->dateTime('date_calculated')->nullable();
            $table->integer('f_years_since_application_created')->nullable();
        });
    }
};
