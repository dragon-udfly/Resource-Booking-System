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
            $table->string('applicant_email')->nullable()->after('applicant_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hall_booking', function (Blueprint $table) {
            $table->dropColumn('applicant_email');
        });
    }
};
