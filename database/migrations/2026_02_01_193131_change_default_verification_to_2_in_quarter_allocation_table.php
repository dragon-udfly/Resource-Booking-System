<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->tinyInteger('is_ao_verified')->default(2)->change();
            $table->tinyInteger('is_aga_verified')->default(2)->change();
        });

        // Update existing 0s to 2 (Pending)
        DB::table('quarter_allocation')->where('is_ao_verified', 0)->update(['is_ao_verified' => 2]);
        DB::table('quarter_allocation')->where('is_aga_verified', 0)->update(['is_aga_verified' => 2]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->tinyInteger('is_ao_verified')->default(0)->change();
            $table->tinyInteger('is_aga_verified')->default(0)->change();
        });
    }
};
