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
        Schema::dropIfExists('cache_locks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If this table was needed, its creation logic would go here.
        // For now, we assume it's not needed based on the user's request.
    }
};
