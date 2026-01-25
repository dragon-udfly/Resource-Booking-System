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
            $table->text('special_notice')->nullable()->after('allowed_gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->dropColumn('special_notice');
        });
    }
};
