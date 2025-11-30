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
        Schema::create('hall', function (Blueprint $table) {
            $table->string('hall_id', 10)->primary();
            $table->string('hall_type', 200);
            $table->integer('capacity');
            $table->string('description', 1200);
            $table->string('current_state', 50);
            $table->string('booking_state', 50);
            $table->dateTime('date_created');
            $table->dateTime('date_modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hall');
    }
};
