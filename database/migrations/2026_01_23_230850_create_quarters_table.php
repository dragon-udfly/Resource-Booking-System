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
        Schema::create('quarters', function (Blueprint $table) {
            $table->string('quarter_id', 20)->primary(); // Set as primary key
            $table->string('old_quarter_no', 50)->nullable();
            $table->string('new_quarter_no', 50)->nullable();
            $table->string('quarter_type', 50)->nullable(false);
            $table->string('location', 100)->nullable();
            $table->string('status', 50)->nullable(false)->default('NOT_ALLOCATED');
            $table->dateTime('date_created');
            $table->dateTime('date_modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quarters');
    }
};
