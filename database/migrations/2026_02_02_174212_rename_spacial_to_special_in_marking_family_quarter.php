<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            $table->renameColumn('f_spacial_reason', 'f_special_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            $table->renameColumn('f_special_reason', 'f_spacial_reason');
        });
    }
};
