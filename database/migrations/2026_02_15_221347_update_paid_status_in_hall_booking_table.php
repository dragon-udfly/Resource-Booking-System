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
        // Update existing 'Yes' values to 'Paid'
        DB::table('hall_booking')->where('paid_status', 'Yes')->update(['paid_status' => 'Paid']);

        // Change the column to ENUM (Only for MySQL/MariaDB)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE hall_booking MODIFY COLUMN paid_status ENUM('Paid', 'Not Required', 'Pending') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the column to VARCHAR (Only for MySQL/MariaDB)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE hall_booking MODIFY COLUMN paid_status VARCHAR(50) NOT NULL");
        }

        // Revert 'Paid' values to 'Yes'
        DB::table('hall_booking')->where('paid_status', 'Paid')->update(['paid_status' => 'Yes']);
    }
};
