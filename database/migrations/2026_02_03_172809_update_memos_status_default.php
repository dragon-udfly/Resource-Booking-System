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
        Schema::table('memos', function (Blueprint $table) {
            $table->tinyInteger('status')->default(2)->comment('0: No, 1: Yes, 2: Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('memos', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0: Pending, 1: Yes, 2: No')->change();
        });
    }
};
