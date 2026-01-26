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
            $table->dropForeign(['quarter_id']);
        });

        Schema::table('quarters', function (Blueprint $table) {
            $table->dropPrimary('quarters_quarter_id_primary'); // Drop the primary key
            $table->string('quarter_id', 100)->change(); // Change the column type
            $table->primary('quarter_id'); // Add the primary key back
        });

        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->foreign('quarter_id')->references('quarter_id')->on('quarters')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->dropForeign(['quarter_id']);
        });

        Schema::table('quarters', function (Blueprint $table) {
            $table->dropPrimary('quarter_id'); // Drop the primary key
            $table->string('quarter_id', 20)->change(); // Revert the column type
            $table->primary('quarter_id'); // Add the primary key back
        });

        Schema::table('quarter_allocation', function (Blueprint $table) {
            $table->foreign('quarter_id')->references('quarter_id')->on('quarters')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
