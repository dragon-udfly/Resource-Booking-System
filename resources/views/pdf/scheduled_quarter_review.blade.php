<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheduled Quarter Application-{{ $application->application_id }}</title>
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
            <h2>Scheduled Quarter Application</h2>
        </div>

        <div class="section-title">A) Officer Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>1. Name of Officer:</strong> <span>{{ $application->officer_name ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>2. NIC Number:</strong> <span>{{ $application->nic ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>3. Designation:</strong> <span>{{ $application->designation ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>4. Gender:</strong> <span>{{ $application->gender ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>5. Service and Grade:</strong> <span>{{ $application->service_grade ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>8. Telephone Number:</strong> <span>{{ $application->phone_number ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>6. Permanent Address:</strong> <span>{{ $application->permanent_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>7. Temporary Address:</strong> <span>{{ $application->temporary_address ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>9. Email Address:</strong> <span>{{ $application->email ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>10. Monthly Salary:</strong> <span>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>11. Date of Assumption of Duties in Vavuniya:</strong> <span>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">B) Special Reasons for Priority Request</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><strong>1. Transferred Officer (Description):</strong> <span>{{ $application->scheduledQuarterApplication?->sq_transfered_officer_priority_request ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>2. Frequent Night Duty (Description):</strong> <span>{{ $application->scheduledQuarterApplication?->sq_night_duty_priority_request ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item full-width"><strong>3. Other Special Reason (Description):</strong> <span>{{ $application->scheduledQuarterApplication?->sq_other_special_reason_priority_request ?? 'N/A' }}</span></div>
            </div>
        </div> 
        
        <div class="section-title">C) Property Ownership Within 5 km of Vavuniya Town</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item full-width"><strong>Do you or your spouse own any house or land within a 5 km radius of Vavuniya town?</strong> <span>{{ $application->scheduledQuarterApplication?->sq_property_ownership_details ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">D) Allocation Process Details</div>
        <div class="details-grid">
            <div class="grid-row">
                <div class="grid-item"><strong>Monthly Salary (excluding allowances):</strong> <span>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Applicant Grade (Service and Grade):</strong> <span>{{ $application->service_grade ?? 'N/A' }}</span></div>
            </div>
            <div class="grid-row">
                <div class="grid-item"><strong>Grade (according to Monthly Salary):</strong> <span>{{ $calculatedGrade ?? 'N/A' }}</span></div>
                <div class="grid-item"><strong>Applicant Gender:</strong> <span>{{ $application->gender ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="section-title">E) Available Scheduled Quarters</div>
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9px;">
            <thead>
                <tr style="background-color: #e9ecef;">
                    <th style="border: 1px solid #dee2e6; padding: 6px; text-align: left;">No.</th>
                    <th style="border: 1px solid #dee2e6; padding: 6px; text-align: left;">Quarters No. (New)</th>
                    <th style="border: 1px solid #dee2e6; padding: 6px; text-align: left;">Quarters No. (Old)</th>
                    <th style="border: 1px solid #dee2e6; padding: 6px; text-align: left;">Vacancies (for Chummary)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availableQuarters as $index => $quarter)
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 6px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 6px;">{{ $quarter->new_quarter_no ?? 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 6px;">{{ $quarter->old_quarter_no ?? 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 6px;">{{ ($quarter->occupant_number - ($quarter->current_occupant_number ?? 0)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="border: 1px solid #dee2e6; padding: 6px; text-align: center;">No available scheduled quarters matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($application->quarterAllocation && $application->quarterAllocation->allocation_status == 'allocated' && $application->quarterAllocation->quarter)
            <div class="section-title">F) Allocated Quarter's Details</div>
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

        <div class="section-title">G) Allocation Details</div>
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