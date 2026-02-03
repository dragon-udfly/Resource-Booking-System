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
            // Drop unwanted columns if they exist
            if (Schema::hasColumn('memos', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('memos', 'sender_status')) {
                $table->dropColumn('sender_status');
            }
            if (Schema::hasColumn('memos', 'receiver_status')) {
                $table->dropColumn('receiver_status');
            }
            if (Schema::hasColumn('memos', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('memos', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            // Add new columns if they don't exist
            if (!Schema::hasColumn('memos', 'sender_cleared')) {
                $table->tinyInteger('sender_cleared')->default(0)->after('body')->comment('0: Visible, 1: Cleared');
            }
            if (!Schema::hasColumn('memos', 'receiver_cleared')) {
                $table->tinyInteger('receiver_cleared')->default(0)->after('sender_cleared')->comment('0: Visible, 1: Cleared');
            }
            if (!Schema::hasColumn('memos', 'date_created')) {
                $table->date('date_created')->nullable()->after('receiver_cleared');
            }
        });
    }

    public function down(): void
    {
        // Revert logic is complicated due to potential data loss, 
        // essentially we just add back dropped columns as nullable.
        Schema::table('memos', function (Blueprint $table) {
            if (!Schema::hasColumn('memos', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('memos', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
        });
    }
};
