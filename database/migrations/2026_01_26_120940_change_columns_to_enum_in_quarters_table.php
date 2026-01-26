<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data to match new ENUM values
        DB::table('quarters')->where('status', 'NOT_ALLOCATED')->update(['status' => 'Unallocated']);
        DB::table('quarters')->where('status', 'OCCUPIED')->update(['status' => 'Allocated']);
        
        DB::table('quarters')->where('quarter_type', 'FAMILY')->update(['quarter_type' => 'Family']);
        DB::table('quarters')->where('quarter_type', 'SCHEDULED_QUARTERS')->update(['quarter_type' => 'Scheduled']);

        DB::table('quarters')->where('allowed_gender', 'F')->update(['allowed_gender' => 'Female']);
        DB::table('quarters')->where('allowed_gender', 'M')->update(['allowed_gender' => 'Male']);

        Schema::table('quarters', function (Blueprint $table) {
            $table->enum('status', ['Unallocated', 'Allocated', 'Repair', 'Demolished'])->change();
            $table->enum('quarter_type', ['Family', 'Scheduled'])->change();
            $table->enum('allowed_gender', ['Male', 'Female'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->string('status', 50)->change();
            $table->string('quarter_type', 50)->change();
            $table->string('allowed_gender', 20)->nullable()->change();
        });
    }
};
