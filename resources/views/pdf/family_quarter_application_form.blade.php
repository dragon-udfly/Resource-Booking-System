<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Family Quarter Application - {{ $application->application_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0056b3;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0056b3;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
            font-weight: normal;
        }

        .application-id {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0056b3;
            border-bottom: 1px solid #0056b3;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-grid {
            margin-bottom: 10px;
            overflow: hidden;
        }

        .grid-row {
            display: flex;
            width: 100%;
            margin-bottom: 6px;
            gap: 15px;
        }

        .grid-item {
            flex: 1;
            min-width: 0;
            /* Allows flex items to shrink below content size */
        }

        .grid-item.full-width {
            width: 100%;
            flex: none;
        }

        .grid-item-label {
            display: block;
            color: #495057;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
            padding: 2px 0;
        }

        .grid-item-value {
            display: block;
            padding: 4px 6px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            font-size: 10px;
            min-height: 18px;
            word-wrap: break-word;
        }

        .section-content {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .section-content p {
            margin-bottom: 5px;
            font-size: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            page-break-inside: avoid;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-allocated {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>District Secretariat, Vavuniya</h1>
            <h2>Family Quarter Application</h2>
        </div>

        <div class="application-id">
            Application ID: {{ $application->application_id }}
        </div>

        <div class="section-title">A) Officer Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">1. Name of Officer:</span> <span
                        class="grid-item-value">{{ $application->officer_name ?? 'N/A' }}</span></div>
                <div class="grid-item"><span class="grid-item-label">2. NIC Number:</span> <span
                        class="grid-item-value">{{ $application->nic ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">3. Date of Birth:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_dob ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">4. Designation:</span> <span
                        class="grid-item-value">{{ $application->designation ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">5. Gender:</span> <span
                        class="grid-item-value">{{ $application->gender ?? 'N/A' }}</span></div>
                <div class="grid-item"><span class="grid-item-label">6. Service and Grade:</span> <span
                        class="grid-item-value">{{ $application->service_grade ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">7. Permanent Address:</span> <span
                        class="grid-item-value">{{ $application->permanent_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">8. Temporary Address:</span> <span
                        class="grid-item-value">{{ $application->temporary_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">9. Monthly Salary (excluding allowances):</span>
                    <span class="grid-item-value">{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">10. Date of Last Salary Increment:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_date_of_last_salary_increment ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">11. Date of Assumption of Duties in
                        Vavuniya:</span> <span
                        class="grid-item-value">{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</span></div>
                <div class="grid-item"><span class="grid-item-label">12. Is applicant a transferred officer?:</span>
                    <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_transformed_officer ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">B) Spouse Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">1. Marital Status:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_marital_status ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">2. Is your spouse employed in government
                        service?:</span> <span
                        class="grid-item-value">{{ isset($application->familyQuarterApplication?->f_is_spouse_employed) ? ($application->familyQuarterApplication?->f_is_spouse_employed ? 'Yes' : 'No') : 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">3. Spouse's Designation:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_spouse_designation ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">4. Department / Office Name:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_spouse_department_office ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">5. Monthly Salary (excluding allowances):</span>
                    <span
                        class="grid-item-value">{{ number_format($application->familyQuarterApplication?->f_spouse_monthly_salary, 2) ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">6. Date of Last Salary Increment:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_spouse_last_increment_date ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">C) Children Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">1. Description of Children:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_children_details_description ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">D) Property Ownership in Vavuniya District</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">Do you or your spouse or children under
                        18 own any land or house in Vavuniya District?</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_property_ownership_details ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">E) Previous Stay in Government Quarters</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Have you previously stayed in government quarters?
                        (Duration in Years):</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->f_previous_government_quarter_duration ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">F) Marking Scheme and Marking</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Total Mark:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->total_mark ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">G) Marking Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Department:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Years Since Application Created:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_years_since_application_created ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Number of Dependants:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Dependant with Disability:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability ? 'Yes' : 'No' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Distance of Residency:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Special Reason:</span> <span
                        class="grid-item-value">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">H) Allocation Process Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Monthly Salary:</span> <span
                        class="grid-item-value">{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span>
                </div>
                <div class="grid-item"><span class="grid-item-label">Applicant Grade (Service and Grade):</span> <span
                        class="grid-item-value">{{ $application->service_grade ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Grade (according to Monthly Salary):</span> <span
                        class="grid-item-value">{{ $calculatedGrade ?? 'N/A' }}</span></div>
                <div class="grid-item"><span class="grid-item-label">Gender:</span> <span
                        class="grid-item-value">{{ $application->gender ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">I) Available Family Quarters</div>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Quarters No. (New)</th>
                    <th>Quarters No. (Old)</th>
                    <th>Vacancies (for Chummary)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availableQuarters as $index => $quarter)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $quarter->new_quarter_no ?? 'N/A' }}</td>
                        <td>{{ $quarter->old_quarter_no ?? 'N/A' }}</td>
                        <td>{{ ($quarter->occupant_number - ($quarter->current_occupant_number ?? 0)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No available family quarters matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($application->quarterAllocation && $application->quarterAllocation->allocation_status == 'allocated' && $application->quarterAllocation->quarter)
            <div class="section-title">J) Allocated Quarter's Details</div>
            <div class="details-grid">
                <div class="grid-row">
                    <div class="grid-item"><span class="grid-item-label">Quarter No. (New):</span> <span
                            class="grid-item-value">{{ $application->quarterAllocation->quarter->new_quarter_no ?? 'N/A' }}</span>
                    </div>
                    <div class="grid-item"><span class="grid-item-label">Quarter No. (Old):</span> <span
                            class="grid-item-value">{{ $application->quarterAllocation->quarter->old_quarter_no ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-item full-width"><span class="grid-item-label">Location:</span> <span
                            class="grid-item-value">{{ $application->quarterAllocation->quarter->location ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-item full-width"><span class="grid-item-label">Quarter Type:</span> <span
                            class="grid-item-value">{{ $application->quarterAllocation->quarter->quarter_type ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="section-title">J) Allocation Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Final Allocation Status:</span> <span
                        class="grid-item-value">
                        @if($application->quarterAllocation?->allocation_status)
                            <span class="status-badge status-{{ $application->quarterAllocation->allocation_status }}">
                                {{ ucfirst($application->quarterAllocation->allocation_status) }}
                            </span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </span></div>
                <div class="grid-item"><span class="grid-item-label">Allocation Date:</span> <span
                        class="grid-item-value">{{ $application->quarterAllocation->allocation_date ? \Carbon\Carbon::parse($application->quarterAllocation->allocation_date)->format('Y-m-d') : 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><span class="grid-item-label">Vacate Date:</span> <span
                        class="grid-item-value">{{ $application->quarterAllocation->vacate_date ? \Carbon\Carbon::parse($application->quarterAllocation->vacate_date)->format('Y-m-d') : 'N/A' }}</span>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">Administrative Officer Note:</span>
                    <span class="grid-item-value">{{ $application->quarterAllocation?->ao_note ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">Additional Government Agent Note:</span>
                    <span class="grid-item-value">{{ $application->quarterAllocation?->aga_note ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><span class="grid-item-label">Government Agent Note:</span> <span
                        class="grid-item-value">{{ $application->quarterAllocation?->ga_note ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on: {{ date('Y-m-d H:i:s') }} | Page <span class="pageNumber"></span>/<span
                    class="totalPages"></span><br>District Secretariat - Vavuniya Hall and Quarters Booking System</p>
        </div>
    </div>
</body>

</html>