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
        Schema::create('property_ownership_family', function (Blueprint $table) {
            $table->increments('property_id');
            $table->unsignedInteger('application_id')->nullable(false);
            $table->enum('owner', ['Officer', 'Spouse', 'Child'])->nullable();
            $table->enum('property_type', ['Land', 'House'])->nullable();
            $table->text('location')->nullable();
            $table->foreign('application_id')->references('application_id')->on('quater_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_ownership_family');
    }
};
