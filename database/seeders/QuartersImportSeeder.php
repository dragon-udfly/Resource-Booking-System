<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Quarter;
use Carbon\Carbon;

class QuartersImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('excel_raw_all.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: $csvFile");
            return;
        }

        $file = fopen($csvFile, 'r');
        $headers = fgetcsv($file); // Skip headers: Row,NewNo,OldNo,Location,Occupant,Type,Grade,Gender,Status

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            // Mapping based on CSV structure:
            // 0:Row, 1:NewNo, 2:OldNo, 3:Location, 4:Occupant, 5:Type, 6:Grade, 7:Gender, 8:Status

            $newNo = trim($row[1]);
            $oldNo = trim($row[2]);
            $location = trim($row[3]);
            $occupant = trim($row[4]);
            $type = trim($row[5]);
            $grade = trim($row[6]) ?: null;
            $gender = trim($row[7]) ?: null;
            $status = trim($row[8]);

            // Determine Primary Key (quarter_id)
            $quarterId = !empty($newNo) ? $newNo : $oldNo;

            if (empty($quarterId)) {
                continue;
            }

            // Cleanup multi-line data if any (though CSV extraction flattened it)
            $location = str_replace(["\r", "\n"], ' ', $location);

            // Adjust grade value to match Enum exactly if needed
            // Our Grade column currently has "5", "4", "3", "2", "1", "5A"
            // The table Enum is ['1', '2', '3', '4', '5', '5A']

            Quarter::updateOrCreate(
                ['quarter_id' => $quarterId],
                [
                    'old_quarter_no' => $oldNo,
                    'new_quarter_no' => $newNo,
                    'quarter_type' => $type,
                    'service_grade' => $grade,
                    'location' => $location,
                    'status' => $status,
                    'allowed_gender' => $gender,
                    'date_created' => Carbon::now(),
                    'date_modified' => Carbon::now(),
                ]
            );
            $count++;
        }

        fclose($file);
        $this->command->info("Imported $count quarters successfully.");
    }
}
