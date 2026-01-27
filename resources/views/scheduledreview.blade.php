@extends('layouts.normal_body_layout')

@section('title', 'Scheduled Quarters Application Review - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .button-bar {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            width: 90%;
            max-width: 1200px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease;
        }

        .home-btn { background-color: #6c757d; }
        .back-btn { background-color: #007bff; }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin-top: 20px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 280px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group p {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            background-color: #f8f9fa;
            min-height: 40px;
        }
        
        .form-section-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0056b3;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            width: 100%;
        }
    </style>
@endsection

@section('content')
{{--
    This view expects an $application object (QuarterApplication model) with the following relationships loaded:
    - scheduledQuarterApplication
    - quarterAllocation
--}}
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
        </div>
        
        <div class="page-header">
            <h2>Application for Scheduled Quarters - Review</h2>
        </div>

        <div class="form-container">
            <h3 class="form-section-title">A) Officer Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Name of Officer:</label>
                    <p>{{ $application->officer_name ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>2. National Identity Card Number:</label>
                    <p>{{ $application->nic ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>3. Designation:</label>
                    <p>{{ $application->designation ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>4. Gender:</label>
                    <p>{{ $application->gender ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>5. Service and Grade:</label>
                    <p>{{ $application->service_grade ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>6. Permanent Address:</label>
                    <p>{{ $application->permanent_address ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>7. Temporary Address:</label>
                    <p>{{ $application->temporary_address ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>8. Telephone Number:</label>
                    <p>{{ $application->phone_number ?? 'N/A' }}</p>
                </div>
            </div> 

            <div class="form-row">
                <div class="form-group">
                    <label>9. Email Address:</label>
                    <p>{{ $application->email ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>10. Monthly Salary (excluding allowances):</label>
                    <p>{{ $application->monthly_salary ?? 'N/A' }}</p>
                </div>
            </div> 
            <div class="form-row">
                <div class="form-group">
                    <label>11. Date of Assumption of Duties in Vavuniya:</label>
                    <p>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">B) Special Reasons for Priority Request</h3>
            <div class="form-row">
               <div class="form-group">
                    <label>1. Are you a transferred officer? (Description, if available)</label>
                    <p>{{ $application->scheduledQuarterApplication?->sq_transfered_officer_priority_request ?? 'N/A' }}</p>
                </div>
            </div> 
            <div class="form-row">
                <div class="form-group">
                    <label>2. Are you frequently called for night duty? (Description, if available)</label>
                    <p>{{ $application->scheduledQuarterApplication?->sq_night_duty_priority_request ?? 'N/A' }}</p>
                </div>
            </div> 
            <div class="form-row">
                <div class="form-group">
                    <label>3. Any other special reason? (Description, if available)</label>
                    <p>{{ $application->scheduledQuarterApplication?->sq_other_special_reason_priority_request ?? 'N/A' }}</p>
                </div>
            </div> 
            
            <h3 class="form-section-title">C) Property Ownership Within 5 km of Vavuniya Town </h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Do you or your spouse own any house or land within a 5 km radius of Vavuniya town?</label>
                    <p>{{ $application->scheduledQuarterApplication?->sq_property_ownership_details ?? 'N/A' }}</p>
                </div>
            </div>
        </div> 

        <div class="form-container">
            <h3 class="form-section-title">Allocation Process Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Monthly Salary:</label>
                    <p>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Applicant Grade (Service and Grade):</label>
                    <p>{{ $application->service_grade ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Grade (according to Monthly Salary):</label>
                    <p>{{ $calculatedGrade ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <p>{{ $application->gender ?? 'N/A' }}</p>
                </div>
            </div>
            <h3 class="form-section-title">Available Scheduled Quarters</h3>
            <div class="form-row">
            </dv>

            <h3 class="form-section-title">Allocation Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>OA Verified:</label>
                    <p>{{ $application->quarterAllocation?->is_oa_verified ? 'Verified' : 'Pending' }}</p>
                </div>
                 <div class="form-group">
                    <label>OA Note:</label>
                    <p>{{ $application->quarterAllocation?->oa_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>AGA Verified:</label>
                    <p>{{ $application->quarterAllocation?->is_aga_verified ? 'Verified' : 'Pending' }}</p>
                </div>
                <div class="form-group">
                    <label>AGA Note:</label>
                    <p>{{ $application->quarterAllocation?->aga_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Allocation Status:</label>
                    <p>{{ $application->quarterAllocation?->allocation_status ?? 'N/A' }}</p>
                </div>
                 <div class="form-group">
                    <label>Allocation Date:</label>
                    <p>{{ $application->quarterAllocation?->allocation_date ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection