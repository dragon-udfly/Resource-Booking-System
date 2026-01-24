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
        Schema::create('previous_quarters_stay', function (Blueprint $table) {
            $table->increments('stay_id');
            $table->unsignedInteger('application_id')->nullable(false);
            $table->integer('duration_years')->nullable();
            $table->foreign('application_id')->references('application_id')->on('quater_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_quarters_stay');
    }
};
