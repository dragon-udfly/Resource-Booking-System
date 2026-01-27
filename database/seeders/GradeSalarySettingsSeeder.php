<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeSalarySetting;
use Carbon\Carbon;

class GradeSalarySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            '1 (G I)' => ['min' => 30000, 'max' => 45000],
            '2 (G II)' => ['min' => 45001, 'max' => 60000],
            '3 (G III)' => ['min' => 60001, 'max' => 75000],
            '4 (G IV)' => ['min' => 75001, 'max' => 90000],
            '5 (G V)' => ['min' => 90001, 'max' => 105000],
        ];

        foreach ($grades as $gradeName => $salaries) {
            GradeSalarySetting::updateOrCreate(
                ['grade' => $gradeName],
                [
                    'min_salary' => $salaries['min'],
                    'max_salary' => $salaries['max'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
