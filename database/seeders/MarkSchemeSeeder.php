<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarkSchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marking_scheme')->insert([
            // 1. Department Section (Max: 20)
            [
                'marking_title' => '1. Department',
                'marking_option' => '1.1 Officers attached under the Ministry of Home Affairs',
                'defined_mark' => 20,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '1. Department',
                'marking_option' => '1.2 Officers attached to District & Divisional Secretariats',
                'defined_mark' => 15,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '1. Department',
                'marking_option' => '1.3 Other Officers (Other Dept.)',
                'defined_mark' => 10,
                'date_modified' => Carbon::now()
            ],

            // 2. Date of Application Section (Max: 20)
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.1 Above 06 years',
                'defined_mark' => 20,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.2 05 years',
                'defined_mark' => 15,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.3 04 years',
                'defined_mark' => 12,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.4 03 years',
                'defined_mark' => 9,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.5 02 years',
                'defined_mark' => 6,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '2. Date of Application',
                'marking_option' => '2.6 01 year',
                'defined_mark' => 3,
                'date_modified' => Carbon::now()
            ],

            // 3. Dependents Section (Max: 20)
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.1 05 or Above 05 persons',
                'defined_mark' => 17,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.2 04 persons',
                'defined_mark' => 12,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.3 03 persons',
                'defined_mark' => 9,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.4 02 persons',
                'defined_mark' => 6,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.5 01 person',
                'defined_mark' => 3,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '3. Dependents',
                'marking_option' => '3.6 Disability Mark (Additional points)',
                'defined_mark' => 3,
                'date_modified' => Carbon::now()
            ],

            // 4. Distance of Residency Section (Max: 30)
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.1 Out District - above 100km',
                'defined_mark' => 30,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.2 Out District - between 51km and 100km',
                'defined_mark' => 25,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.3 Out District - between 26km and 50km',
                'defined_mark' => 20,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.4 Out District - below 25km',
                'defined_mark' => 15,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.5 Out of Urban Council Area above 30km',
                'defined_mark' => 10,
                'date_modified' => Carbon::now()
            ],
            [
                'marking_title' => '4. Distance of Residency',
                'marking_option' => '4.6 Out of Urban Council Area between 00km and 30km',
                'defined_mark' => 5,
                'date_modified' => Carbon::now()
            ],

            // 5. Special Reason Section (Max: 10)
            [
                'marking_title' => '5. Special Reason',
                'marking_option' => '5.1 Special reason decided by GA',
                'defined_mark' => 10,
                'date_modified' => Carbon::now()
            ],
        ]);
    }
}
