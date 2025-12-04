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
        Schema::create('user_permissions', function (Blueprint $table) {
            // FIX 1: Use the standard id() without arguments, or specify the name cleanly.
            // Using bigIncrements for primary key as standard practice.
            $table->id('permission_id'); 

            // FIX 2: Foreign Key MUST be a string(100) to match the primary key in the 'user' table.
            $table->string('user_id', 100)->unique(); 
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');

            // Permission columns (using boolean for 1 or 0)
            $table->boolean('view_officers')->default(0);
            $table->boolean('view_officer_details')->default(0);
            
            $table->boolean('view_halls')->default(0);
            $table->boolean('view_hall_details')->default(0);
            
            $table->boolean('view_quarters')->default(0);
            $table->boolean('view_quarter_details')->default(0);
            
            $table->boolean('view_audit_log')->default(0);

            $table->boolean('administrative_officer_approval')->default(0);
            $table->boolean('additional_government_agent_approval')->default(0);
            $table->boolean('government_agent_approval')->default(0);
            
            $table->boolean('form_history')->default(0);
            $table->boolean('account_setting')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};