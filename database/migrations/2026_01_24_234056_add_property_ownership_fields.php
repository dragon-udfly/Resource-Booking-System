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
        Schema::table('quater_applications', function (Blueprint $table) {
            $table->boolean('property_within_km')->default(false)->after('phone');
        });

        Schema::table('property_ownership_family', function (Blueprint $table) {
            $table->integer('duration_years')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quater_applications', function (Blueprint $table) {
            $table->dropColumn('property_within_km');
        });

        Schema::table('property_ownership_family', function (Blueprint $table) {
            $table->dropColumn('duration_years');
        });
    }
};
