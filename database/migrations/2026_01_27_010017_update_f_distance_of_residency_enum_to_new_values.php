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
        Schema::disableForeignKeyConstraints();

        Schema::table('marking_family_quarter', function (Blueprint $table) {
            $table->enum('f_distance_of_residency', [
                'Out_District_above_100km',
                'Out_District_between_51km_and_100km',
                'Out_District_between_26km_and_50km',
                'Out_District_below_25km',
                'Out_of_Urban_Council_Area_above_30km',
                'Out_of_Urban_Council_Area_between_00km_and_30km'
            ])->nullable()->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('marking_family_quarter', function (Blueprint $table) {
            // Revert to the previous ENUM values.
            // Assuming the previous values were:
            $table->enum('f_distance_of_residency', [
                'OUT_DISTRICT_ABOVE_100KM',
                'OUT_DISTRICT_51_TO_100KM',
                'OUT_DISTRICT_26_TO_50KM',
                'Out_District_below_25km', // This was the updated value from previous migration
                'OUT_URBAN_ABOVE_30KM',
                'OUT_URBAN_0_TO_30KM'
            ])->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
