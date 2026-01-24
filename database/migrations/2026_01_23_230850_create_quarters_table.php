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
            $table->increments('quarter_id'); // Primary key with custom name
            $table->string('old_quarter_no', 50)->nullable();
            $table->string('new_quarter_no', 50)->nullable();
            $table->enum('quarter_type', ['NORMAL', 'FAMILY'])->nullable(false);
            $table->string('location', 100)->nullable();
            $table->enum('status', ['OCCUPIED', 'NOT_ALLOCATED', 'REPAIR', 'DEMOLISHED'])->nullable(false)->default('NOT_ALLOCATED');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('modified_at')->useCurrent()->useCurrentOnUpdate();
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
