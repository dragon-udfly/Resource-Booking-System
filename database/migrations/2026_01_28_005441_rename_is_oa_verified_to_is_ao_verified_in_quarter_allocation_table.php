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
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->renameColumn('is_oa_verified', 'is_ao_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->renameColumn('is_ao_verified', 'is_oa_verified');
        });
    }
};
