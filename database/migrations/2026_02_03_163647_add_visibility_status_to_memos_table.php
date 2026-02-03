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
            $table->tinyInteger('sender_status')->default(1)->after('is_read')->comment('1: Visible, 0: Deleted');
            $table->tinyInteger('receiver_status')->default(1)->after('sender_status')->comment('1: Visible, 0: Deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memos', function (Blueprint $table) {
            $table->dropColumn(['sender_status', 'receiver_status']);
        });
    }
};
