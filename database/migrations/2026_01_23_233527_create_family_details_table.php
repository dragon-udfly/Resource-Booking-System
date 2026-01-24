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
        Schema::create('family_details', function (Blueprint $table) {
            $table->increments('family_id');
            $table->unsignedInteger('application_id')->nullable(false);
            $table->enum('marital_status', ['MARRIED', 'WIDOWED', 'DIVORCED', 'SEPARATED'])->nullable(false);
            $table->enum('dependants', ['01_PERSON', '02_PERSONS', '03_PERSONS', '04_PERSONS', '05_OR_ABOVE'])->nullable(false);
            $table->boolean('spouse_employed')->nullable();
            $table->string('spouse_designation', 100)->nullable();
            $table->decimal('spouse_salary', 10, 2)->nullable();
            $table->date('spouse_last_increment')->nullable();
            $table->foreign('application_id')->references('application_id')->on('quater_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_details');
    }
};
