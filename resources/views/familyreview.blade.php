@extends('layouts.user_body_layout')

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

        textarea {
            resize: vertical;
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

        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-download { background-color: #17a2b8; color: white; }
        .btn-info { background-color: #17a2b8; color: white; }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .marking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .marking-table th,
        .marking-table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
            font-size: 1.2em;
        }

        .marking-table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }

        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); text-align: center; max-width: 450px; width: 90%; transform: scale(0.9); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-content h3 { margin-top: 0; color: #333; }
        .modal-content p { margin-bottom: 20px; color: #555; }
        .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .btn-secondary { background-color: #6c757d; color: white; }

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
                    <p>{!! nl2br(e($application->familyQuarterApplication?->f_children_details_description ?? 'N/A')) !!}</p>
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
                    <label>Total Mark:</label>
                    <p style="font-weight: bold; font-size: 1.2em; color: #0056b3;">{{ $markingBreakdown['total'] ?? 0 }}</p>
                </div>
            </div>

            <table class="marking-table">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Selection</th>
                        <th>Mark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Applicant's Department</td>
                        <td>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department ?? 'N/A' }}</td>
                        <td>{{ $markingBreakdown['department'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Seniority (Years since application)</td>
                        <td>
                            @if($application->date_created)
                                {{ floor(\Carbon\Carbon::parse($application->date_created)->diffInYears(\Carbon\Carbon::now())) }} years
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $markingBreakdown['seniority'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Number of Dependant</td>
                        <td>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant ?? 'N/A' }}</td>
                        <td>{{ $markingBreakdown['dependents'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Dependant(s) with Disability</td>
                        <td>{{ isset($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability) ? ($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability ? 'Yes' : 'No') : 'N/A' }}</td>
                        <td>{{ $markingBreakdown['disability'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Distance of Residency</td>
                        <td>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency ?? 'N/A' }}</td>
                        <td>{{ $markingBreakdown['distance'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #f0f8ff; font-weight: bold;">Special Reasons (Provided During Submission)</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 15px;">
                            @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                                <textarea name="f_special_reason" 
                                          form="review-form"
                                          rows="4" 
                                          class="form-control" 
                                          placeholder="Enter special reasons or circumstances for this application"
                                          style="width: 100%; border: 1px solid #ced4da; border-radius: 4px; padding: 8px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason ?? '' }}</textarea>
                                <small style="color: #6c757d;">Government Agent can add/edit special reasons</small>
                            @else
                                {{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason ?? 'Not Mentioned' }}
                            @endif
                        </td>
                        <td>-</td>
                    </tr>
                    @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                        <tr style="background-color: #fff3cd;">
                            <td colspan="3" style="font-weight: bold; color: #856404;">
                                <strong>⚠ Manual Marks for Special Reasons (Government Agent Only)</strong>
                            </td>
                        </tr>
                        <tr style="background-color: #fff3cd;">
                            <td>Additional Marks for Special Circumstances</td>
                            <td>
                                <input type="number" 
                                       name="f_special_reason_marks" 
                                       form="review-form"
                                       min="0" 
                                       max="10" 
                                       value="{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason_marks ?? 0 }}" 
                                       class="form-control"
                                       style="width: 100px;">
                                <small style="color: #856404;">Max: 10 marks</small>
                            </td>
                            <td>{{ $markingBreakdown['special_reason'] ?? 0 }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>Special Reason Marks (Assigned by GA)</td>
                            <td>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason_marks ?? 0 }}</td>
                            <td>{{ $markingBreakdown['special_reason'] ?? 0 }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
           <br />
        </div>
        <div class="form-container">
            <form id="review-form"
            action="{{ route('family-quarter.allocate', ['id' => $application->application_id]) }}" method="POST">@csrf

                <h3 class="form-section-title">G) Allocation Process Details</h3>
                <div class="form-row">
                    <div class="form-group"><label>Monthly Salary:</label><p>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</p></div>
                    <div class="form-group"><label>Applicant Grade (Service):</label><p>{{ $application->service_grade ?? 'N/A' }}</p></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Grade (Calculated):</label><p>{{ $calculatedGrade ?? 'N/A' }}</p></div>
                    <div class="form-group"><label>Gender:</label><p>{{ $application->gender ?? 'N/A' }}</p></div>
                </div>

                <h3 class="form-section-title">H) Available Family Quarters</h3>
                <div class="form-row">
                    <table style="width:100%;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Quarters No. (New)</th>
                                <th>Quarters No. (Old)</th>
                                <th>Vacancies</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableQuarters as $index => $quarter)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $quarter->new_quarter_no ?? 'N/A' }}</td>
                                    <td>{{ $quarter->old_quarter_no ?? 'N/A' }}</td>
                                    <td>{{ ($quarter->occupant_number - ($quarter->current_occupant_number ?? 0)) }}</td>
                                    <td>
                                        <input type="radio" name="selected_quarter" value="{{ $quarter->quarter_id }}" id="quarter_{{ $quarter->quarter_id }}" class="quarter-radio">
                                        <label for="quarter_{{ $quarter->quarter_id }}">Select</label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;">No available scheduled quarters matching the criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h3 class="form-section-title">I) Allocation Details</h3>
                {{-- AO Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                <div class="form-row">
                    <div class="form-group">
                        <label for="ao_verified_status">Administrative Officer Verified:</label>
                        <select name="ao_verified_status" id="ao_verified_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;">
                            <option value="">-- Select an Action --</option>
                            <option value="1" @if(optional($application->quarterAllocation)->is_ao_verified == 1) selected @endif>Yes</option>
                            <option value="0" @if(optional($application->quarterAllocation)->is_ao_verified === 0) selected @endif>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ao_note">Administrative Officer Note:</label>
                        <textarea name="ao_note" id="ao_note" rows="3" class="form-control" style="width: 100%;">{{ optional($application->quarterAllocation)->ao_note ?? '' }}</textarea>
                    </div>
                </div>
                @else
                <div class="form-row">
                    <div class="form-group"><label>Administrative Officer Verified:</label>
                        @php
                            $aoStatus = optional($application->quarterAllocation)->is_ao_verified;
                        @endphp
                        <p>
                            @if($aoStatus === 1) Yes 
                            @elseif($aoStatus === 0) No 
                            @else Pending 
                            @endif
                        </p>
                    </div>
                    <div class="form-group"><label>Administrative Officer Note:</label><p>{{ optional($application->quarterAllocation)->ao_note ?? 'N/A' }}</p></div>
                </div>
                @endif

                {{-- AGA Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('additional_government_agent_approval'))
                <div class="form-row">
                    <div class="form-group">
                        <label for="aga_verified_status">Additional Government Agent Verified:</label>
                        <select name="aga_verified_status" id="aga_verified_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;">
                            <option value="">-- Select an Action --</option>
                            <option value="1" @if(optional($application->quarterAllocation)->is_aga_verified == 1) selected @endif>Yes</option>
                            <option value="0" @if(optional($application->quarterAllocation)->is_aga_verified === 0) selected @endif>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aga_note">Additional Government Agent Note:</label>
                        <textarea name="aga_note" id="aga_note" rows="3" class="form-control" style="width: 100%;">{{ optional($application->quarterAllocation)->aga_note ?? '' }}</textarea>
                    </div>
                </div>
                @else
                <div class="form-row">
                    <div class="form-group"><label>Additional Government Agent Verified:</label>
                        @php
                            $agaStatus = optional($application->quarterAllocation)->is_aga_verified;
                        @endphp
                        <p>
                            @if($agaStatus === 1) Yes 
                            @elseif($agaStatus === 0) No 
                            @else Pending 
                            @endif
                        </p>
                    </div>
                    <div class="form-group"><label>Additional Government Agent Note:</label><p>{{ optional($application->quarterAllocation)->aga_note ?? 'N/A' }}</p></div>
                </div>
                @endif
                
                {{-- GA Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ga_approval_status">Government Agent Approved:</label>
                            <select name="ga_approval_status" id="ga_approval_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;">
                                <option value="">-- Select an Action --</option>
                                <option value="1" {{ optional($application->quarterAllocation)->allocation_status === 'allocated' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ optional($application->quarterAllocation)->allocation_status === 'rejected' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ga_note">Government Agent Note:</label>
                            <textarea name="ga_note" id="ga_note" rows="3" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;" placeholder="Review notice for Government Agent">{{ old('ga_note', optional($application->quarterAllocation)->ga_note ?? '') }}</textarea>
                        </div>
                    </div>
                @else
                    <div class="form-row">
                        <div class="form-group">
                            <label>Government Agent Approved:</label>
                            @php
                                $gaStatus = optional($application->quarterAllocation)->allocation_status;
                            @endphp
                            <p>
                                @if($gaStatus === 'allocated') Yes 
                                @elseif($gaStatus === 'rejected') No 
                                @else Pending 
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Government Agent Note:</label>
                            <p>{{ optional($application->quarterAllocation)->ga_note ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Final Allocation Status:</label>
                        <p style="font-weight: bold; text-transform: capitalize;">{{ optional($application->quarterAllocation)->allocation_status ?? 'N/A' }}</p>
                    </div>
                     <div class="form-group">
                        <label>Allocation Date:</label>
                        <p>{{ optional($application->quarterAllocation)->allocation_date ? \Carbon\Carbon::parse($application->quarterAllocation->allocation_date)->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="button-group">
                    @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                        {{-- if ga_approval_status is 1(Yes) and selected_quarter is not empty--}}
                        {{-- update quarter_id and is_ga_verified and ga_note and allocation_status and allocation_date in quarter_allocation--}}
                        {{-- update vacate_date, allocation date + 5 years --}}
                        <button type="submit" name="action" value="allocate" id="allocate-button" class="btn btn-success">Allocate</button>
                        {{-- if ga_approval_status in 0(No) --}}
                        {{-- update is_ga_verified and ga_note and allocation_status in quarter_allocation--}}
                        <button type="submit" name="action" value="reject" id="reject-button" class="btn btn-danger">Reject</button>
                    @endif
                    @if(Auth::user()->hasPermissionTo('additional_government_agent_approval'))
                        {{-- update is_aga_verified and aga_note in quarter_allocation--}}
                        <button type="submit" name="action" value="Submit" id="submit-button" class="btn btn-success">Submit</button>
                    @endif
                    @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                        {{-- update is_ao_verified and ao_note --}}
                        <button type="submit" name="action" value="Submit" id="submit-button" class="btn btn-success">Submit</button>
                        {{-- AO can delete if allocation_status is pending --}}
                        @php
                            $canAODelete = optional($application->quarterAllocation)->allocation_status === 'pending';
                        @endphp
                        <button type="button" id="delete-button" class="btn btn-danger" @if(!$canAODelete) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>Delete</button>
                    @endif
                    @if(Auth::user()->hasPermissionTo('requester'))
                        {{-- Requester can delete only if is_ao_verified=0, is_aga_verified=0, allocation_status=pending --}}
                        @php
                            $canRequesterDelete = optional($application->quarterAllocation)->is_ao_verified == 2 
                                               && optional($application->quarterAllocation)->is_aga_verified == 2
                                               && optional($application->quarterAllocation)->allocation_status === 'pending';
                        @endphp
                        <button type="button" id="delete-button" class="btn btn-danger" @if(!$canRequesterDelete) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>Delete</button>
                    @endif
                    {{-- All users can download pdf --}}
                    <a href="{{ route('quarter.download-pdf', ['id' => $application->application_id]) }}" class="btn btn-download" target="_blank">Download</a>
                </div>
            </form>
        </div>

    <!-- Generic Modal Overlay -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <div id="modal-buttons" class="modal-buttons">
                <!-- Buttons will be injected by JavaScript -->
            </div>
        </div>
    </div>

    {{-- Processing Overlay --}}
    <div id="processing-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; flex-direction: column;">
        <div style="background: white; padding: 30px; border-radius: 8px; text-align: center;">
            <div class="spinner"
                style="border: 4px solid #f3f3f3; border-top: 4px solid #007bff; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px;">
            </div>
            <h3 style="margin: 0;">Processing...</h3>
            <p style="margin-top: 10px; color: #666;">Please wait while we update the application status.</p>
        </div>
    </div>

    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rejectBtn = document.getElementById('reject-button');
    const form = document.getElementById('review-form');
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalButtons = document.getElementById('modal-buttons');

    const showModal = (title, message, buttons) => {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalButtons.innerHTML = '';
        buttons.forEach(btn => {
            const buttonEl = document.createElement('button');
            buttonEl.textContent = btn.text;
            buttonEl.className = btn.class;
            buttonEl.addEventListener('click', btn.onClick);
            modalButtons.appendChild(buttonEl);
        });
        modalOverlay.classList.add('active');
    };

    const hideModal = () => {
        modalOverlay.classList.remove('active');
    };

    const showProcessingOverlay = () => {
        document.getElementById('processing-overlay').style.display = 'flex';
    };

    const hideProcessingOverlay = () => {
        document.getElementById('processing-overlay').style.display = 'none';
    };

        // Allocate Button Handler (for GA only)
    const allocateBtn = document.getElementById('allocate-button');
    if (allocateBtn) {
        const gaApprovalStatus = document.getElementById('ga_approval_status');
        const quarterRadios = document.querySelectorAll('.quarter-radio'); // Get all radio buttons
        
        // Function to update allocate button state
        const updateAllocateButton = () => {
            const isGaApproved = gaApprovalStatus && gaApprovalStatus.value === '1';
            let isQuarterSelected = false;
            
            quarterRadios.forEach(radio => {
                if (radio.checked) {
                    isQuarterSelected = true;
                }
            });

            console.log('GA Approved:', isGaApproved, 'Quarter Selected:', isQuarterSelected);

            if (isGaApproved && isQuarterSelected) {
                allocateBtn.disabled = false;
                allocateBtn.style.opacity = '1';
                allocateBtn.style.cursor = 'pointer';
                allocateBtn.style.backgroundColor = '#28a745';
            } else {
                allocateBtn.disabled = true;
                allocateBtn.style.opacity = '0.5';
                allocateBtn.style.cursor = 'not-allowed';
                allocateBtn.style.backgroundColor = '#6c757d';
            }
        };

        // Set initial state
        updateAllocateButton();
        
        // Listeners
        if (gaApprovalStatus) {
            gaApprovalStatus.addEventListener('change', updateAllocateButton);
        }
        
        quarterRadios.forEach(radio => {
            radio.addEventListener('change', updateAllocateButton);
        });

        // Click handler for allocate button
        allocateBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const gaApprovalValue = gaApprovalStatus ? gaApprovalStatus.value : '';
            let selectedQuarter = null;
            quarterRadios.forEach(radio => {
                if (radio.checked) selectedQuarter = radio.value;
            });

            // Validation
            if (gaApprovalValue !== '1') {
                const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                showModal('Validation Error', 'GA approval must be set to "Yes" to allocate.', buttons);
                return;
            }

            if (!selectedQuarter) {
                const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                showModal('Validation Error', 'Please select a quarter from the available list.', buttons);
                return;
            }

            // Confirmation Dialog
            const confirmButtons = [
                { text: 'Yes, Allocate', class: 'btn btn-success', onClick: () => performAllocation() },
                { text: 'Cancel', class: 'btn btn-secondary', onClick: hideModal }
            ];
            showModal('Confirm Allocation', 'Are you sure you want to allocate this family quarter application to Quarter ' + selectedQuarter + '?', confirmButtons);
        });

        const performAllocation = async () => {
            // Updated to use new overlay
            hideModal(); // Hide confirmation modal first
            showProcessingOverlay(); // Show full screen spinner

            const formData = new FormData(form);
            // Manually append the action 'allocate' since it's not included in FormData when submitting via fetch/js
            formData.append('action', 'allocate');

            const url = form.getAttribute('action');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorButtons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
                } else {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        const successButtons = [
                            { text: 'Go to Dashboard', class: 'btn btn-success', onClick: () => window.location.href = '/dashboard' },
                            { text: 'Stay on Page', class: 'btn btn-info', onClick: () => window.location.reload() }
                        ];
                        showModal('Success', result.message, successButtons);
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server. Please check your network connection.', errorButtons);
            }
        };
    }

    // Reject Button Handler (for GA only)
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function (e) {
            e.preventDefault();

            const gaApprovalStatus = document.getElementById('ga_approval_status');
            const agaVerifiedStatus = document.getElementById('aga_verified_status');
            const gaNote = document.getElementById('ga_note');

            // Validation: GA approval must be set to "No" or AGA verified must be set to "No"
            if (gaApprovalStatus && gaApprovalStatus.value !== '0') {
                const buttons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Validation Error', 'Government Agent approval must be set to "No" to reject an application.', buttons);
                return;
            } else if (!gaApprovalStatus && agaVerifiedStatus && agaVerifiedStatus.value !== '0') {
                 const buttons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Validation Error', 'Additional Government Agent verification must be set to "No" to reject an application.', buttons);
                return;
            }

            // New Validation: GA Note Required for Rejection
            if (gaApprovalStatus && (!gaNote || !gaNote.value.trim())) {
                const buttons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Validation Error', 'Please provided a note explaining the reason for rejection.', buttons);
                return;
            }


            // Confirmation Dialog
            const confirmButtons = [
                { 
                    text: 'Yes, Reject', 
                    class: 'btn btn-danger', 
                    onClick: () => performRejection() 
                },
                { text: 'Cancel', class: 'btn btn-secondary', onClick: hideModal }
            ];
            showModal('Confirm Rejection', 'Are you sure you want to reject this application?', confirmButtons);
        });

        const performRejection = async () => {
            // Updated to use new overlay
            hideModal(); // Hide confirmation modal first
            showProcessingOverlay(); // Show full screen spinner

            const formData = new FormData(form);
            // Append explicit action for clarity, though route handles it
            formData.append('action', 'reject');

            // Determine URL based on who is rejecting
            // If GA (ga_approval_status exists), use the dedicated reject route
            // If AGA/AO (aga_verified_status exists), keep using original form action (review update)
            let url = form.getAttribute('action'); 
            const gaApprovalStatus = document.getElementById('ga_approval_status');
            
            if (gaApprovalStatus) {
                // GA Rejection -> Switch to reject route
                 url = url.replace('/allocate', '/reject');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorButtons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
                } else {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        const successButtons = [
                            { text: 'Go to Dashboard', class: 'btn btn-success', onClick: () => window.location.href = '/dashboard' },
                            { text: 'Stay on Page', class: 'btn btn-info', onClick: () => window.location.reload() }
                        ];
                        showModal('Success', result.message, successButtons);
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server. Please check your network connection.', errorButtons);
            }
        };
    }

    // Delete Button Handler
    const deleteBtn = document.getElementById('delete-button');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Check if button is disabled and show informative message
            if (deleteBtn.disabled) {
                const infoButtons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                showModal('Cannot Delete', 'This application cannot be deleted because it has already been reviewed or processed. Only fresh pending applications with no verifications can be deleted.', infoButtons);
                return;
            }

            // Confirmation Dialog
            const confirmButtons = [
                { text: 'Yes, Delete', class: 'btn btn-danger', onClick: () => performDeletion() },
                { text: 'Cancel', class: 'btn btn-secondary', onClick: hideModal }
            ];
            showModal('Confirm Deletion', 'Are you sure you want to delete this application? This action cannot be undone.', confirmButtons);
        });

        const performDeletion = async () => {
            const loadingButtons = [];
            showModal('Processing...', 'Deleting application, please wait...', loadingButtons);

            // Extract application ID from current page URL
            const currentUrl = window.location.pathname;
            const match = currentUrl.match(/\/family-quarter-application\/([^\/]+)/);
            let applicationId = '';
            if (match && match[1]) {
                applicationId = match[1];
            } else {
                console.error("Could not extract application ID from URL:", currentUrl);
                showModal('Error', 'Failed to extract application ID from page URL.', [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }]);
                return;
            }

            const url = `/family-quarter-application/${applicationId}/delete`;

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorButtons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                    showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
                } else {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        const successButtons = [{ text: 'OK', class: 'btn btn-success', onClick: () => window.location.reload() }];
                        showModal('Success', result.message, successButtons);
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server. Please check your network connection.', errorButtons);
            }
        };
    }

    // Submit Button Handler for AO and AGA
    const submitBtn = document.getElementById('submit-button');
    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            // Check if this is AO or AGA
            const aoVerifiedStatus = document.getElementById('ao_verified_status');
            const aoNote = document.getElementById('ao_note');
            const agaVerifiedStatus = document.getElementById('aga_verified_status');
            const agaNote = document.getElementById('aga_note');

            // Validation for AO
            if (aoVerifiedStatus && aoNote) {
                const status = aoVerifiedStatus.value;
                const note = aoNote.value.trim();

                if (status === '') {
                    e.preventDefault();
                    const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Validation Error', 'Please select Yes or No for Administrative Officer Verified.', buttons);
                    return;
                }

                if (status === '0' && note === '') {
                    e.preventDefault();
                    const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Validation Error', 'Administrative Officer Note is required when verification is set to No.', buttons);
                    return;
                }
            }

            // Validation for AGA
            if (agaVerifiedStatus && agaNote) {
                const status = agaVerifiedStatus.value;
                const note = agaNote.value.trim();

                if (status === '') {
                    e.preventDefault();
                    const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Validation Error', 'Please select Yes or No for Additional Government Agent Verified.', buttons);
                    return;
                }

                if (status === '0' && note === '') {
                    e.preventDefault();
                    const buttons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Validation Error', 'Additional Government Agent Note is required when verification is set to No.', buttons);
                    return;
                }
            }

            // If validation passes, show confirmation
            e.preventDefault();
            const confirmButtons = [
                { text: 'Yes, Submit', class: 'btn btn-success', onClick: () => performSubmission() },
                { text: 'Cancel', class: 'btn btn-secondary', onClick: hideModal }
            ];
            showModal('Confirm Submission', 'Are you sure you want to submit this review?', confirmButtons);
        });

        const performSubmission = async () => {
            const loadingButtons = [];
            showModal('Processing...', 'Submitting verification, please wait...', loadingButtons);

            const formElement = document.getElementById('review-form');
            const formData = new FormData(formElement);
            formData.append('action', 'Submit');
            const url = formElement.getAttribute('action');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorButtons = [{ text: 'OK', class: 'btn btn-info', onClick: hideModal }];
                    showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
                } else {
                    const successButtons = [
                        { text: 'Go to Dashboard', class: 'btn btn-success', onClick: () => window.location.href = result.redirect_url },
                        { text: 'Stay on Page', class: 'btn btn-info', onClick: () => window.location.reload() }
                    ];
                    showModal('Success', result.message, successButtons);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'btn btn-danger', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server. Please check your network connection.', errorButtons);
            }
        };
    }
});
</script>
@endpush