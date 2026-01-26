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
        Schema::table('quarters', function (Blueprint $table) {
            $table->enum('service_grade', ['1', '2', '3', '4', '5', '5A'])->nullable()->default(null)->after('quarter_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->dropColumn('service_grade');
        });
    }
};
