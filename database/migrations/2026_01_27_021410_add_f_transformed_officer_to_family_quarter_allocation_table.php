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
        Schema::table('family_quarter_application', function (Blueprint $table) {
            $table->text('f_transformed_officer')->nullable()->after('f_date_of_last_salary_increment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_quarter_application', function (Blueprint $table) {
            $table->dropColumn('f_transformed_officer');
        });
    }
};
