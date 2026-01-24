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
        Schema::create('normal_priority_details', function (Blueprint $table) {
            $table->increments('priority_id');
            $table->unsignedInteger('application_id')->nullable(false);
            $table->boolean('is_transferred')->nullable();
            $table->boolean('night_duty')->nullable();
            $table->text('other_reason')->nullable();
            $table->foreign('application_id')->references('application_id')->on('quater_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normal_priority_details');
    }
};
