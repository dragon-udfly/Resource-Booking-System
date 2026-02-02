<?php

namespace App\Services;

use App\Models\FamilyQuarterApplication;
use App\Models\MarkingScheme;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarkingCalculatorService
{
    public function calculateScore(FamilyQuarterApplication $application)
    {
        $totalScore = 0;
        $markingRecord = $application->markingFamilyQuarter;

        if (!$markingRecord) {
            return 0;
        }

        // 1. Department (20 Marks)
        if ($markingRecord->f_department) {
            $totalScore += $this->getMark($markingRecord->f_department);
        }

        // 2. Date of Application / Seniority (20 Marks)
        if ($application->quarterApplication && $application->quarterApplication->date_created) {
            $appDate = Carbon::parse($application->quarterApplication->date_created);
            // Use float diff to handle >= checks appropriately
            $years = $appDate->diffInYears(Carbon::now());

            $seniorityKey = '';
            // Logic based on "Above 06 years" -> 20, "05 years" -> 15 (Completed years)
            if ($years >= 6)
                $seniorityKey = 'seniority_above_06_years';
            elseif ($years >= 5)
                $seniorityKey = 'seniority_05_years';
            elseif ($years >= 4)
                $seniorityKey = 'seniority_04_years';
            elseif ($years >= 3)
                $seniorityKey = 'seniority_03_years';
            elseif ($years >= 2)
                $seniorityKey = 'seniority_02_years';
            elseif ($years >= 1)
                $seniorityKey = 'seniority_01_year';

            if ($seniorityKey) {
                $totalScore += $this->getMark($seniorityKey);
            }
        }

        // 3. Dependents (20 Marks)
        if ($markingRecord->f_number_of_dependant) {
            $depKey = '';
            $val = $markingRecord->f_number_of_dependant;

            if ($val == '05_or_above_05_person')
                $depKey = 'dependents_05_or_above';
            elseif ($val == '04_person')
                $depKey = 'dependents_04';
            elseif ($val == '03_person')
                $depKey = 'dependents_03';
            elseif ($val == '02_person')
                $depKey = 'dependents_02';
            elseif ($val == '01_person')
                $depKey = 'dependents_01';

            if ($depKey) {
                $totalScore += $this->getMark($depKey);
            }
        }

        // Disability Bonus (3 Marks)
        if ($markingRecord->is_dependant_with_disability) {
            $totalScore += $this->getMark('disability_bonus');
        }

        // 4. Distance of Residency (30 Marks)
        if ($markingRecord->f_distance_of_residency) {
            $totalScore += $this->getMark($markingRecord->f_distance_of_residency);
        }

        // 5. Special Reason (manual)

        $markingRecord->total_mark = $totalScore;
        $markingRecord->date_calculated = Carbon::now();
        $markingRecord->save();

        return $totalScore;
    }

    private function getMark($key)
    {
        $scheme = MarkingScheme::where('marking_option', $key)->first();
        return $scheme ? $scheme->defined_mark : 0;
    }
}
