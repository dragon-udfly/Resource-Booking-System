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
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            // 1. Rename typo column if it exists and correct one doesn't
            if (Schema::hasColumn('marking_family_quarter', 'f_spacial_reason') && !Schema::hasColumn('marking_family_quarter', 'f_special_reason')) {
                $table->renameColumn('f_spacial_reason', 'f_special_reason');
            }

            // 2. Add special_reason_marks if missing
            if (!Schema::hasColumn('marking_family_quarter', 'f_special_reason_marks')) {
                $table->integer('f_special_reason_marks')->nullable()->after('f_distance_of_residency'); // approximate position
            }

            // 3. Remove unused columns if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('marking_family_quarter', 'total_mark')) {
                $columnsToDrop[] = 'total_mark';
            }
            if (Schema::hasColumn('marking_family_quarter', 'date_calculated')) {
                $columnsToDrop[] = 'date_calculated';
            }
            if (Schema::hasColumn('marking_family_quarter', 'f_years_since_application_created')) {
                $columnsToDrop[] = 'f_years_since_application_created';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marking_family_quarter', function (Blueprint $table) {
            // Reverse logic roughly (not critical for a fix migration, but good practice)
            if (Schema::hasColumn('marking_family_quarter', 'f_special_reason')) {
                $table->renameColumn('f_special_reason', 'f_spacial_reason');
            }
            $table->dropColumn('f_special_reason_marks');
            $table->integer('total_mark')->nullable();
            $table->dateTime('date_calculated')->nullable();
            $table->integer('f_years_since_application_created')->nullable();
        });
    }
};
