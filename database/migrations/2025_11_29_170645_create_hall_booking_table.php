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
        Schema::create('hall_booking', function (Blueprint $table) {
            $table->string('booking_id', 20)->primary();
            $table->string('applicant_name', 200);
            $table->string('applicant_type', 50);
            $table->string('requested_hall_type', 200);
            $table->string('hall_id', 10)->nullable();
            $table->foreign('hall_id')->references('hall_id')->on('hall')->onDelete('set null');
            $table->string('programme', 200);
            $table->date('event_date');
            $table->integer('participants');
            $table->float('event_duration');
            $table->string('paid_status', 50);
            $table->boolean('is_emergency_booking')->default(false);
            $table->string('filled_by_nic', 50);
            $table->string('filled_by_phone', 50);
            $table->boolean('administrative_officer_approved')->default(false);
            $table->boolean('additional_government_agent_approved')->default(false);
            $table->boolean('government_agent_approved')->default(false);
            $table->boolean('final_approval')->default(false);
            $table->dateTime('date_created');
            $table->dateTime('date_modified')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hall_booking');
    }
};
