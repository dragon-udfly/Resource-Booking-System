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
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_lock');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If these tables were needed, their creation logic would go here.
        // For now, we assume they are not needed based on the user's request.
    }
};
