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
            $table->dropColumn(['is_read', 'sender_status', 'receiver_status', 'created_at', 'updated_at']);

            $table->tinyInteger('sender_cleared')->default(0)->after('body')->comment('0: Visible, 1: Cleared');
            $table->tinyInteger('receiver_cleared')->default(0)->after('sender_cleared')->comment('0: Visible, 1: Cleared');
            $table->date('date_created')->nullable()->after('receiver_cleared');
        });
    }

    public function down(): void
    {
        Schema::table('memos', function (Blueprint $table) {
            $table->boolean('is_read')->default(false);
            $table->tinyInteger('sender_status')->default(1);
            $table->tinyInteger('receiver_status')->default(1);
            $table->timestamps();

            $table->dropColumn(['sender_cleared', 'receiver_cleared', 'date_created']);
        });
    }
};
