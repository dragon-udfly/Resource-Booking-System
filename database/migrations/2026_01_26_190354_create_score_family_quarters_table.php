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
        Schema::create('score_family_quarters', function (Blueprint $table) {
            $table->increments('score_id');
            $table->string('f_application_id', 100);
            $table->enum('f_department', [
                'Officers_attached_under_the_Ministry_of_Home_Affairs',
                'Officers_attached_to_District_and_Divisional_Secretariats',
                'Other_Officers'
            ])->nullable();
            $table->integer('f_years_since_application_created')->nullable();
            $table->enum('f_number_of_dependant', [
                '01_person',
                '02_person',
                '03_person',
                '04_person',
                '05_or_above_05_person'
            ])->nullable();
            $table->boolean('is_dependant_with_disability')->default(false);
            $table->enum('f_distance_of_residency', [
                'Out_District_above_100km',
                'Out_District_between_51km_and_100km',
                'Out_District_between_26km_and_50km',
                'Our_District_below_25km',
                'Out_of_Urban_Council_Area_above_30km',
                'Out_of_Urban_Council_Area_between_00km_and_30km'
            ])->nullable();
            $table->text('f_spacial_reason')->nullable();
            $table->integer('total_score')->nullable();
            $table->dateTime('date_calculated')->nullable();

            $table->foreign('f_application_id')->references('f_application_id')->on('family_quarter_application')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('score_family_quarters', function (Blueprint $table) {
            $table->dropForeign(['f_application_id']);
        });
        Schema::dropIfExists('score_family_quarters');
    }
};
