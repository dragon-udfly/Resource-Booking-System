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
        Schema::rename('score_family_quarters', 'marking_family_quarter');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('marking_family_quarter', 'score_family_quarters');
    }
};
