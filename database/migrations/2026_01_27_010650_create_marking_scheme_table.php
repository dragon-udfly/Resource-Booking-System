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
        Schema::create('marking_scheme', function (Blueprint $table) {
            $table->increments('marking_id');
            $table->string('marking_title');
            $table->string('marking_option', 200);
            $table->integer('defined_mark');
            $table->datetime('date_modified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marking_scheme');
    }
};
