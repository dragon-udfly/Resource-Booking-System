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
        Schema::create('quarter_allocation', function (Blueprint $table) {
            $table->increments('allocation_id');
            $table->string('application_id', 100);
            $table->string('quarter_id', 100);
            $table->boolean('is_aga_verified')->default(false);
            $table->text('aga_note')->nullable();
            $table->boolean('is_oa_verified')->default(false);
            $table->text('oa_note')->nullable();
            $table->enum('allocation_status', ['pending', 'allocated', 'rejected'])->default('pending');
            $table->date('allocation_date')->nullable();
            $table->date('vacate_date')->nullable();
            $table->timestamps();

            $table->foreign('application_id')->references('application_id')->on('quarter_application')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('quarter_id')->references('quarter_id')->on('quarters')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['quarter_id']);
        });
        Schema::dropIfExists('quarter_allocation');
    }
};
