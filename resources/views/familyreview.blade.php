@extends('layouts.normal_body_layout')

@section('title', 'Family Quarters Application Review - District Secretariat Vavuniya')

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
    - familyQuarterApplication
    - quarterAllocation
    - familyQuarterApplication.markingFamilyQuarter
--}}
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
        </div>
        
        <div class="page-header">
            <h2>Application for Family Quarters - Review</h2>
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
                    <label>3. Date of Birth:</label>
                    <p>{{ $application->familyQuarterApplication?->f_dob ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>4. Designation:</label>
                    <p>{{ $application->designation ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>5. Gender:</label>
                    <p>{{ $application->gender ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>6. Service and Grade:</label>
                    <p>{{ $application->service_grade ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>7. Permanent Address:</label>
                    <p>{{ $application->permanent_address ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>8. Temporary Address:</label>
                    <p>{{ $application->temporary_address ?? 'N/A' }}</p>
                </div>
            </div> 

            <div class="form-row">
                <div class="form-group">
                    <label>10. Telephone Number:</label>
                    <p>{{ $application->phone_number ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>11. Email Address:</label>
                    <p>{{ $application->email ?? 'N/A' }}</p>
                </div>
            </div> 
            <div class="form-row">
                <div class="form-group">
                    <label>9.  Monthly Salary (excluding allowances):</label>
                    <p>{{ $application->monthly_salary ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>13.  Date of Last Salary Increment:</label>
                    <p>{{ $application->familyQuarterApplication?->f_date_of_last_salary_increment ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>12.  Date of Assumption of Duties in Vavuniya:</label>
                    <p>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>14.  Is applicant a transferred officer?</label>
                    <p>{{ $application->familyQuarterApplication?->f_transformed_officer ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">B) Spouse Details</h3>
            <div class="form-row">
               <div class="form-group">
                    <label>1. Marital Status:</label>
                    <p>{{ $application->familyQuarterApplication?->f_marital_status ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>2. Is your spouse employed in government service?</label>
                    <p>{{ isset($application->familyQuarterApplication?->f_is_spouse_employed) ? ($application->familyQuarterApplication?->f_is_spouse_employed ? 'Yes' : 'No') : 'N/A' }}</p>
                </div>
            </div> 

            <div class="form-row">
                <div class="form-group">
                    <label>3. Spouse’s Designation:</label>
                    <p>{{ $application->familyQuarterApplication?->f_spouse_designation ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>4. Department / Office Name:</label>
                    <p>{{ $application->familyQuarterApplication?->f_spouse_department_office ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>5. Monthly Salary (excluding allowances):</label>
                    <p>{{ $application->familyQuarterApplication?->f_spouse_monthly_salary ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>6. Date of Last Salary Increment:</label>
                    <p>{{ $application->familyQuarterApplication?->f_spouse_last_increment_date ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">C) Children Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Description of Children:</label>
                    <p>{{ $application->familyQuarterApplication?->f_children_details_description ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">D) Property Ownership in Vavuniya District</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Do you or your spouse or children under 18 own any land or house in Vavuniya District?</label>
                    <p>{{ $application->familyQuarterApplication?->f_property_ownership_details ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">E) Previous Stay in Government Quarters</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Have you previously stayed in government quarters? (Duration in Years):</label>
                    <p>{{ $application->familyQuarterApplication?->f_previous_government_quarter_duration ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">F) Marking Scheme and Marking</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Applicant's Department:</label>
                    <p>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>2. Number of Dependant:</label>
                    <p>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>3. Dependant(s) with Disability:</label>
                     <p>{{ isset($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability) ? ($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability ? 'Yes' : 'No') : 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>3. Distance of Residency:</label>
                    <p>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>4. Special Reasons:</label>
                    <p>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_spacial_reason ?? 'N/A' }}</p>
                </div>
            </div>

            <h3 class="form-section-title">G) Requester Details</h3> 
            <div class="form-row">
                <div class="form-group">
                    <label>Requester Officer's NIC:</label>
                    <p>{{ $application->filled_by_nic ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Requester Officer's Phone:</label>
                    <p>{{ $application->filled_by_phone ?? 'N/A' }}</p>
                </div>
            </div>

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
