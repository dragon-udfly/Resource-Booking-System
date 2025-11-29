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
        Schema::create('audit_log', function (Blueprint $table) {
            $table->increments('audit_log_id');
            $table->string('log_title', 200);
            $table->string('performed_by', 100);
            $table->foreign('performed_by')->references('user_id')->on('user')->onDelete('cascade');
            $table->date('date_performed');
            $table->time('time_performed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
