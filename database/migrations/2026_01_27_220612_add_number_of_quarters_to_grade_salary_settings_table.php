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
        Schema::table('grade_salary_settings', function (Blueprint $table) {
            $table->integer('number_of_quarters')->default(0)->after('max_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_salary_settings', function (Blueprint $table) {
            $table->dropColumn('number_of_quarters');
        });
    }
};
