<?php

namespace App\Services;

use App\Models\FamilyQuarterApplication;
use App\Models\MarkingScheme;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarkingCalculatorService
{
    public function calculateDynamicScore(FamilyQuarterApplication $application)
    {
        $breakdown = [
            'department' => 0,
            'seniority' => 0,
            'dependents' => 0,
            'disability' => 0,
            'distance' => 0,
            'special_reason' => 0,
            'total' => 0,
        ];

        $markingRecord = $application->markingFamilyQuarter;

        if (!$markingRecord) {
            return $breakdown;
        }

        // 1. Department (20 Marks)
        if ($markingRecord->f_department) {
            $breakdown['department'] = $this->getMark($markingRecord->f_department);
        }

        // 2. Date of Application / Seniority (15 Marks MAX)
        if ($application->quarterApplication && $application->quarterApplication->date_created) {
            $appDate = Carbon::parse($application->quarterApplication->date_created);
            $years = $appDate->diffInYears(Carbon::now());

            $seniorityKey = '';
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
                $breakdown['seniority'] = $this->getMark($seniorityKey);
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
                $breakdown['dependents'] = $this->getMark($depKey);
            }
        }

        // Disability Bonus (3 Marks)
        if ($markingRecord->is_dependant_with_disability) {
            $breakdown['disability'] = $this->getMark('disability_bonus');
        }

        // 4. Distance of Residency (30 Marks)
        if ($markingRecord->f_distance_of_residency) {
            $breakdown['distance'] = $this->getMark($markingRecord->f_distance_of_residency);
        }

        // 5. Special Reason (Manual - up to 15 marks)
        if ($markingRecord->f_special_reason_marks) {
            $breakdown['special_reason'] = $markingRecord->f_special_reason_marks;
        }

        // Calculate total
        $breakdown['total'] = array_sum(array_filter($breakdown, function ($key) {
            return $key !== 'total';
        }, ARRAY_FILTER_USE_KEY));

        return $breakdown;
    }

    private function getMark($key)
    {
        $scheme = MarkingScheme::where('marking_option', $key)->first();
        return $scheme ? $scheme->defined_mark : 0;
    }
}
