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
        Schema::dropIfExists('memo_recipients');
        Schema::dropIfExists('memos');

        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id')->nullable();
            $table->string('receiver_id')->nullable();
            $table->text('subject'); // Encrypted
            $table->longText('body'); // Encrypted
            $table->tinyInteger('status')->default(0)->comment('0: Pending, 1: Yes, 2: No');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Foreign Keys (assuming user table id is user_id and string)
            $table->foreign('sender_id')->references('user_id')->on('user')->onDelete('set null');
            $table->foreign('receiver_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
