<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarkingScheme;
use Carbon\Carbon;

class MarkingSchemeSeeder extends Seeder
{
    public function run()
    {
        // Clear existing to avoid duplicates if re-running
        MarkingScheme::truncate();

        $schemes = [
            // 1. Department (20 Marks)
            ['title' => 'Officers_attached_under_the_Ministry_of_Home_Affairs', 'mark' => 20],
            ['title' => 'Officers_attached_to_District_and_Divisional_Secretariats', 'mark' => 15],
            ['title' => 'Other_Officers', 'mark' => 10],

            // 2. Date of Application (Seniority - 20 Marks)
            ['title' => 'seniority_above_06_years', 'mark' => 20],
            ['title' => 'seniority_05_years', 'mark' => 15],
            ['title' => 'seniority_04_years', 'mark' => 12],
            ['title' => 'seniority_03_years', 'mark' => 9],
            ['title' => 'seniority_02_years', 'mark' => 6],
            ['title' => 'seniority_01_year', 'mark' => 3],

            // 3. Dependents (20 Marks)
            ['title' => 'dependents_05_or_above', 'mark' => 17],
            ['title' => 'dependents_04', 'mark' => 12],
            ['title' => 'dependents_03', 'mark' => 9],
            ['title' => 'dependents_02', 'mark' => 6],
            ['title' => 'dependents_01', 'mark' => 3],

            // Disability Bonus
            ['title' => 'disability_bonus', 'mark' => 3],

            // 4. Distance of Residency (30 Marks)
            // Note: Enum keys from migration should map to these
            ['title' => 'Out_District_above_100km', 'mark' => 30],
            ['title' => 'Out_District_between_51km_and_100km', 'mark' => 25],
            ['title' => 'Out_District_between_26km_and_50km', 'mark' => 20],
            ['title' => 'Our_District_below_25km', 'mark' => 15], // "Out District" in image, likely typo fix in enum
            ['title' => 'Out_of_Urban_Council_Area_above_30km', 'mark' => 10],
            ['title' => 'Out_of_Urban_Council_Area_between_00km_and_30km', 'mark' => 5],
        ];

        foreach ($schemes as $item) {
            MarkingScheme::create([
                'marking_title' => ucwords(str_replace('_', ' ', $item['title'])),
                'marking_option' => $item['title'],
                'defined_mark' => $item['mark'],
                'date_modified' => Carbon::now(),
            ]);
        }
    }
}
