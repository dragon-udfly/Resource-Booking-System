<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Family Quarter Application</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; font-size: 10px; }
        .container { width: 90%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header h2 { margin: 5px 0; font-size: 16px; color: #555; }
        .section-title { font-size: 14px; font-weight: bold; color: #0056b3; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 20px; margin-bottom: 10px; }
        .details-grid { margin-bottom: 15px; overflow: hidden;}
        .grid-row { display: block; width: 100%; clear: both; margin-bottom: 5px; }
        .grid-item { float: left; width: 48%; margin-right: 4%; }
        .grid-item:nth-child(even) { margin-right: 0; float: right; }
        .grid-item.full-width { width: 100%; float: none; margin-right: 0; }
        .grid-item strong { display: block; color: #555; font-size: 9px; }
        .grid-item span { display: block; padding: 4px 6px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 2px; font-size: 10px; min-height: 20px;}

        .section-content { margin-top: 10px; margin-bottom: 10px; }
        .section-content p { margin-bottom: 5px; font-size: 10px; }

        .footer { text-align: center; margin-top: 30px; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>District Secretariat, Vavuniya</h1>
            <h2>Family Quarter Application - Application ID: {{ $application->application_id }}</h2>
        </div>

        <div class="section-title">A) Officer Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>1. Name of Officer:</strong> <span>{{ $application->officer_name ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>2. NIC Number:</strong> <span>{{ $application->nic ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>3. Date of Birth:</strong> <span>{{ $application->familyQuarterApplication?->f_dob ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>4. Designation:</strong> <span>{{ $application->designation ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>5. Gender:</strong> <span>{{ $application->gender ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>6. Service and Grade:</strong> <span>{{ $application->service_grade ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>7. Permanent Address:</strong> <span>{{ $application->permanent_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>8. Temporary Address:</strong> <span>{{ $application->temporary_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>9. Monthly Salary (excluding allowances):</strong> <span>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>10. Date of Last Salary Increment:</strong> <span>{{ $application->familyQuarterApplication?->f_date_of_last_salary_increment ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>11. Date of Assumption of Duties in Vavuniya:</strong> <span>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>12. Is applicant a transferred officer?:</strong> <span>{{ $application->familyQuarterApplication?->f_transformed_officer ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">B) Spouse Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>1. Marital Status:</strong> <span>{{ $application->familyQuarterApplication?->f_marital_status ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>2. Is your spouse employed in government service?:</strong> <span>{{ isset($application->familyQuarterApplication?->f_is_spouse_employed) ? ($application->familyQuarterApplication?->f_is_spouse_employed ? 'Yes' : 'No') : 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>3. Spouse's Designation:</strong> <span>{{ $application->familyQuarterApplication?->f_spouse_designation ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>4. Department / Office Name:</strong> <span>{{ $application->familyQuarterApplication?->f_spouse_department_office ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>5. Monthly Salary (excluding allowances):</strong> <span>{{ number_format($application->familyQuarterApplication?->f_spouse_monthly_salary, 2) ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>6. Date of Last Salary Increment:</strong> <span>{{ $application->familyQuarterApplication?->f_spouse_last_increment_date ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">C) Children Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><strong>1. Description of Children:</strong> <span>{{ $application->familyQuarterApplication?->f_children_details_description ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">D) Property Ownership in Vavuniya District</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><strong>Do you or your spouse or children under 18 own any land or house in Vavuniya District?</strong> <span>{{ $application->familyQuarterApplication?->f_property_ownership_details ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">E) Previous Stay in Government Quarters</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Have you previously stayed in government quarters? (Duration in Years):</strong> <span>{{ $application->familyQuarterApplication?->f_previous_government_quarter_duration ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">F) Marking Scheme and Marking</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Total Mark:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->total_mark ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">G) Marking Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Department:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Years Since Application Created:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_years_since_application_created ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>Number of Dependants:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Dependant with Disability:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability ? 'Yes' : 'No' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>Distance of Residency:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Special Reason:</strong> <span>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_spacial_reason ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">H) Allocation Process Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Monthly Salary:</strong> <span>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Applicant Grade (Service and Grade):</strong> <span>{{ $application->service_grade ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>Grade (according to Monthly Salary):</strong> <span>{{ $calculatedGrade ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Gender:</strong> <span>{{ $application->gender ?? 'N/A' }}</span></div>
            </div>
        </div>

        @if($application->quarterAllocation && $application->quarterAllocation->allocation_status == 'allocated' && $application->quarterAllocation->quarter)
            <div class="section-title">I) Allocated Quarter's Details</div>
            <div class="details-grid">
                <div class="grid-row">
                    <div class="grid-item"><strong>Quarter No. (New):</strong> <span>{{ $application->quarterAllocation->quarter->new_quarter_no ?? 'N/A' }}</span></div>
                    <div class="grid-item"><strong>Quarter No. (Old):</strong> <span>{{ $application->quarterAllocation->quarter->old_quarter_no ?? 'N/A' }}</span></div>
                </div>
                <div class="grid-row">
                    <div class="grid-item full-width"><strong>Location:</strong> <span>{{ $application->quarterAllocation->quarter->location ?? 'N/A' }}</span></div>
                </div>
                <div class="grid-row">
                    <div class="grid-item full-width"><strong>Quarter Type:</strong> <span>{{ $application->quarterAllocation->quarter->quarter_type ?? 'N/A' }}</span></div>
                </div>
            </div>
        @endif

        <div class="section-title">J) Allocation Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Final Allocation Status:</strong> <span style="font-weight: bold; text-transform: capitalize;">{{ $application->quarterAllocation?->allocation_status ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Allocation Date:</strong> <span>{{ $application->quarterAllocation->allocation_date ? \Carbon\Carbon::parse($application->quarterAllocation->allocation_date)->format('Y-m-d') : 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>Vacate Date:</strong> <span>{{ $application->quarterAllocation->vacate_date ? \Carbon\Carbon::parse($application->quarterAllocation->vacate_date)->format('Y-m-d') : 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>Administrative Officer Note:</strong> <span>{{ $application->quarterAllocation?->ao_note ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>Additional Government Agent Note:</strong> <span>{{ $application->quarterAllocation?->aga_note ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>Government Agent Note:</strong> <span>{{ $application->quarterAllocation?->ga_note ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on: {{ date('Y-m-d H:i:s') }}<br>District Secretariat - Vavuniya Hall and Quarters Booking System</p>
        </div>
    </div>
</body>
</html>