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
        Schema::create('quater_applications', function (Blueprint $table) {
            $table->increments('application_id');
            $table->enum('quarter_type', ['NORMAL', 'FAMILY'])->nullable(false);
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->string('officer_name', 100)->nullable(false);
            $table->string('nic', 20)->nullable(false);
            $table->date('dob')->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('service_grade', 50)->nullable();
            $table->enum('department', ['MINISTRY_HOME_AFFAIRS', 'DISTRICT_DIVISIONAL_SECRETARIAT', 'OTHER_OFFICERS'])->nullable(false);
            $table->text('permanent_address')->nullable();
            $table->text('temporary_address')->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->date('date_of_last_salary_increment')->nullable();
            $table->date('duty_assumed_date')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('distance_of_residency', ['OUT_DISTRICT_ABOVE_100KM', 'OUT_DISTRICT_51_TO_100KM', 'OUT_DISTRICT_26_TO_50KM', 'OUR_DISTRICT_BELOW_25KM', 'OUT_URBAN_ABOVE_30KM', 'OUT_URBAN_0_TO_30KM'])->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('modified_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quater_applications');
    }
};
