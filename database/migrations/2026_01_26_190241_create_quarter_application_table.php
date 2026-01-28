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
        Schema::create('quarter_application', function (Blueprint $table) {
            $table->string('application_id', 100)->primary();
            $table->enum('quarter_type', ['Family', 'Scheduled'])->nullable();
            $table->string('officer_name', 255)->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('nic', 20)->nullable();
            $table->string('designation', 100)->nullable();
            $table->enum('service_grade', ['1', '2', '3', '4', '5', '5A'])->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('temporary_address')->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('date_of_assumption_of_duties')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quarter_application');
    }
};
