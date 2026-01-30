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
        Schema::create('family_quarter_application', function (Blueprint $table) {
            $table->string('f_application_id', 100)->primary();
            $table->string('application_id', 100);
            $table->date('f_dob')->nullable();
            $table->date('f_date_of_last_salary_increment')->nullable();
            $table->enum('f_marital_status', ['Married', 'Widowed', 'Divorced', 'Separated'])->nullable();
            $table->boolean('f_is_spouse_employed')->nullable();
            $table->string('f_spouse_designation', 100)->nullable();
            $table->string('f_spouse_department_office', 255)->nullable();
            $table->decimal('f_spouse_monthly_salary', 10, 2)->nullable();
            $table->date('f_spouse_last_increment_date')->nullable();
            $table->text('f_children_details_description')->nullable();
            $table->text('f_property_ownership_details')->nullable();
            $table->integer('f_previous_government_quarter_duration')->nullable();

            $table->foreign('application_id')->references('application_id')->on('quarter_application')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_quarter_application', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
        });
        Schema::dropIfExists('family_quarter_application');
    }
};
