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
        Schema::create('user', function (Blueprint $table) {
            $table->string('user_id', 100)->primary(); 
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('designation', 200);
            $table->string('nic_number', 50)->unique(); // Assuming NIC must be unique
            $table->string('email', 200)->unique(); // Assuming email must be unique
            $table->string('contact_number', 10)->unique();
            $table->string('role', 10); 
            $table->datetime('created_datetime');
            $table->datetime('modified_datatime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};