<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuarterApplication;
use App\Models\QuarterApplication;
use App\Models\FamilyQuarterApplication;
use App\Models\ScheduledQuarterApplication;
use Illuminate\Support\Facades\DB;

class QuarterApplicationController extends Controller
{
    /**
     * Display the quarter application form.
     */
    public function create()
    {
        return view('quarterapplication');
    }

    /**
     * Store a newly created quarter application in storage.
     */
    public function store(StoreQuarterApplication $request)
    {
        $validatedData = $request->validated();
        $application_id = uniqid('qa_');

        DB::transaction(function () use ($validatedData, $application_id, $request) {
            $quarterApplication = QuarterApplication::create([
                'application_id' => $application_id,
                'quarter_type' => $validatedData['quarter_type'],
                'officer_name' => $validatedData['officer_name'],
                'gender' => $validatedData['gender'],
                'nic' => $validatedData['nic'],
                'designation' => $validatedData['designation'],
                'service_grade' => $validatedData['service_grade'],
                'permanent_address' => $validatedData['permanent_address'],
                'temporary_address' => $validatedData['temporary_address'],
                'monthly_salary' => $validatedData['monthly_salary'],
                'phone_number' => $validatedData['phone_number'],
                'email' => $validatedData['email'],
                'date_of_assumption_of_duties' => $validatedData['date_of_assumption_of_duties'],
                'date_created' => now(),
            ]);

            if ($validatedData['quarter_type'] === 'Family') {
                FamilyQuarterApplication::create([
                    'f_application_id' => uniqid('fqa_'),
                    'application_id' => $application_id,
                    'f_dob' => $request->input('f_dob'),
                    'f_date_of_last_salary_increment' => $request->input('f_date_of_last_salary_increment'),
                    'f_marital_status' => $request->input('f_marital_status'),
                    'f_is_spouse_employed' => $request->input('f_is_spouse_employed'),
                    'f_spouse_designation' => $request->input('f_spouse_designation'),
                    'f_spouse_department_office' => $request->input('f_spouse_department_office'),
                    'f_spouse_monthly_salary' => $request->input('f_spouse_monthly_salary'),
                    'f_spouse_last_increment_date' => $request->input('f_spouse_last_increment_date'),
                    'f_children_details_description' => $request->input('f_children_details_description'),
                    'f_property_ownership_details' => $request->input('f_property_ownership_details'),
                    'f_previous_government_quarter_duration' => $request->input('f_previous_government_quarter_duration'),
                ]);
            } elseif ($validatedData['quarter_type'] === 'Scheduled') {
                ScheduledQuarterApplication::create([
                    'sq_application_id' => uniqid('sqa_'),
                    'application_id' => $application_id,
                    'sq_transfered_officer_priority_request' => $request->input('sq_transfered_officer_priority_request'),
                    'sq_night_duty_priority_request' => $request->input('sq_night_duty_priority_request'),
                    'sq_other_special_reason_priority_request' => $request->input('sq_other_special_reason_priority_request'),
                    'sq_property_ownership_details' => $request->input('sq_property_ownership_details'),
                ]);
            }
        });

        return redirect()->route('home')->with('success', 'Quarter application submitted successfully.');
    }
}
