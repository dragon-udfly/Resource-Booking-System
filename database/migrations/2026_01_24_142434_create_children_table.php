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
        Schema::create('children', function (Blueprint $table) {
            $table->increments('child_id');
            $table->unsignedInteger('application_id')->nullable(false);
            $table->string('child_name', 100)->nullable();
            $table->integer('age')->nullable();
            $table->string('grade', 20)->nullable();
            $table->string('school', 100)->nullable();
            $table->foreign('application_id')->references('application_id')->on('quater_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
