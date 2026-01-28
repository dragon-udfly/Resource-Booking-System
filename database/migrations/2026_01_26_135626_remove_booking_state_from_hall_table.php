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
        Schema::table('hall', function (Blueprint $table) {
            $table->dropColumn('booking_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hall', function (Blueprint $table) {
            $table->string('booking_state', 50)->after('current_state');
        });
    }
};
