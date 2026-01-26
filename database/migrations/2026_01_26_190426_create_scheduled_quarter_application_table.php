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
        Schema::create('scheduled_quarter_application', function (Blueprint $table) {
            $table->string('sq_application_id', 100)->primary();
            $table->string('application_id', 100);
            $table->text('sq_transfered_officer_priority_request')->nullable();
            $table->text('sq_night_duty_priority_request')->nullable();
            $table->text('sq_other_special_reason_priority_request')->nullable();
            $table->text('sq_property_ownership_details')->nullable();

            $table->foreign('application_id')->references('application_id')->on('quarter_application')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduled_quarter_application', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
        });
        Schema::dropIfExists('scheduled_quarter_application');
    }
};
