@extends('layouts.user_body_layout')

@section('title', 'Scheduled Quarters Application Review - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 1.8em; margin-bottom: 10px; }
        .button-bar { display: flex; justify-content: flex-start; gap: 15px; margin-bottom: 20px; width: 90%; max-width: 1200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; color: white; transition: background-color 0.3s ease; }
        .home-btn { background-color: #6c757d; }
        .back-btn { background-color: #007bff; }
        .btn:hover { opacity: 0.9; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 1200px; margin-top: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; min-width: 280px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group p { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em; background-color: #f8f9fa; min-height: 40px; }
        .form-section-title { font-size: 1.5em; font-weight: bold; margin-bottom: 20px; color: #0056b3; border-bottom: 2px solid #eee; padding-bottom: 10px; width: 100%; }
        .form-container table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .form-container th, .form-container td { border: 1px solid #dee2e6; padding: 8px 12px; text-align: left; font-size: 1.2em; }
        .form-container th { background-color: #e9ecef; font-weight: bold; color: #495057; }
        .form-container tr:nth-child(even) { background-color: #f8f9fa; }
        .form-container tr:hover { background-color: #85b6e7; }
        .form-container input[type="radio"] { width: auto; margin-right: 5px; vertical-align: middle; }
        .btn-success { background-color: #28a745; }
        .btn-danger { background-color: #dc3545; }
        .btn-info { background-color: #17a2b8; }
        .button-group { display: flex; justify-content: center; gap: 15px; margin-top: 30px; }
        .alert { padding: 15px; margin: 0 auto 20px auto; border: 1px solid transparent; border-radius: 4px; width: 90%; max-width: 1200px; text-align: center; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); text-align: center; max-width: 450px; width: 90%; }
        .modal-content h3 { margin-bottom: 20px; }
        .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
        </div>
        
        <div class="page-header">
            <h2>Application for Scheduled Quarters - Review</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <h3 class="form-section-title">A) Officer Details</h3>
            <div class="form-row">
                <div class="form-group"><label>1. Name of Officer:</label><p>{{ $application->officer_name ?? 'N/A' }}</p></div>
                <div class="form-group"><label>2. NIC Number:</label><p>{{ $application->nic ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>3. Designation:</label><p>{{ $application->designation ?? 'N/A' }}</p></div>
                <div class="form-group"><label>4. Gender:</label><p>{{ $application->gender ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>5. Service and Grade:</label><p>{{ $application->service_grade ?? 'N/A' }}</p></div>
                <div class="form-group"><label>6. Permanent Address:</label><p>{{ $application->permanent_address ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>7. Temporary Address:</label><p>{{ $application->temporary_address ?? 'N/A' }}</p></div>
                <div class="form-group"><label>8. Telephone Number:</label><p>{{ $application->phone_number ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>9. Email Address:</label><p>{{ $application->email ?? 'N/A' }}</p></div>
                <div class="form-group"><label>10. Monthly Salary:</label><p>{{ $application->monthly_salary ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>11. Date of Assumption of Duties:</label><p>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</p></div>
            </div>

            <h3 class="form-section-title">B) Special Reasons for Priority Request</h3>
            <div class="form-row">
               <div class="form-group"><label>1. Transferred officer?</label><p>{{ $application->scheduledQuarterApplication?->sq_transfered_officer_priority_request ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>2. Frequent night duty?</label><p>{{ $application->scheduledQuarterApplication?->sq_night_duty_priority_request ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>3. Other special reason?</label><p>{{ $application->scheduledQuarterApplication?->sq_other_special_reason_priority_request ?? 'N/A' }}</p></div>
            </div>
            
            <h3 class="form-section-title">C) Property Ownership</h3>
            <div class="form-row">
                <div class="form-group"><label>1. Owns property within 5km?</label><p>{{ $application->scheduledQuarterApplication?->sq_property_ownership_details ?? 'N/A' }}</p></div>
            </div>
        </div> 

        <div class="form-container">
                <h3 class="form-section-title">D) Allocation Process Details</h3>
                <div class="form-row">
                    <div class="form-group"><label>Monthly Salary:</label><p>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</p></div>
                    <div class="form-group"><label>Applicant Grade (Service):</label><p>{{ $application->service_grade ?? 'N/A' }}</p></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Grade (Calculated):</label><p>{{ $calculatedGrade ?? 'N/A' }}</p></div>
                    <div class="form-group"><label>Gender:</label><p>{{ $application->gender ?? 'N/A' }}</p></div>
                </div>

                <h3 class="form-section-title">E) Available Scheduled Quarters</h3>
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

                <h3 class="form-section-title">F) Allocation Details</h3>
                
                {{-- AO Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                <div class="form-row">
                    <div class="form-group">
                        <label for="ao_verified_status">Administrative Officer Verified:</label>
                        <select name="ao_verified_status" id="ao_verified_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;" required>
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
                    <div class="form-group"><label>Administrative Officer Verified:</label><p>{{ optional($application->quarterAllocation)->is_ao_verified ? 'Yes' : 'No' }}</p></div>
                    <div class="form-group"><label>Administrative Officer Note:</label><p>{{ optional($application->quarterAllocation)->ao_note ?? 'N/A' }}</p></div>
                </div>
                @endif

                {{-- AGA Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('additional_government_agent_approval'))
                <div class="form-row">
                    <div class="form-group">
                        <label for="aga_verified_status">Additional Government Agent Verified:</label>
                        <select name="aga_verified_status" id="aga_verified_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;" required>
                            <option value="">-- Select an Action --</option>
                            <option value="1" @if(optional($application->quarterAllocation)->is_aga_verified == 1) selected @endif>Yes</option>
                            <option value="0" @if(optional($application->quarterAllocation)->is_aga_verified === 0) selected @endif>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aga_note">Additional Government Agent Note:</label>
                        <textarea name="aga_note" id="aga_note" rows="3" class="form-control" style="width: 100%;" readonly>{{ optional($application->quarterAllocation)->aga_note ?? '' }}</textarea>
                    </div>
                </div>
                @else
                <div class="form-row">
                    <div class="form-group"><label>Additional Government Agent Verified:</label><p>{{ optional($application->quarterAllocation)->is_aga_verified ? 'Yes' : 'No' }}</p></div>
                    <div class="form-group"><label>Additional Government Agent Note:</label><p>{{ optional($application->quarterAllocation)->aga_note ?? 'N/A' }}</p></div>
                </div>
                @endif
                
                {{-- GA Specific Controls --}}
                @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ga_approval_status">Government Agent Approved:</label>
                            <select name="ga_approval_status" id="ga_approval_status" class="form-control" style="width: 100%; padding: 8px 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em;" required>
                                <option value="" selected>-- Select an Action --</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
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
                            <p>{{ optional($application->quarterAllocation)->allocation_status !== 'pending' && optional($application->quarterAllocation)->allocation_status !== 'rejected' ? 'Yes' : 'No' }}</p>
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
                        {{-- if aga_verified_status= 0 (No) --}}
                        {{-- update is_aga_verified and aga_note in quarter_allocation--}}
                        <button type="submit" name="action" value="Reject" id="reject-button" class="btn btn-danger">Reject</button>
                    @endif
                    @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                        {{-- update is_ao_verified and ao_note --}}
                        <button type="submit" name="action" value="Submit" id="submit-button" class="btn btn-success">Submit</button>
                        {{-- can cancel is_ao_verified= is 1/0 and is_aga_verified is 1/0 application and allocation_state is pending --}}
                        <button type="submit" name="action" value="Delete" id="delete-pending-verified-button" class="btn btn-success">Delete</button>
                    @endif
                    @if(Auth::user()->hasPermissionTo('requester'))
                        {{-- can cancel only is_ao_verified is 0 and is_aga_verified is 0 allocation_state is pending --}}
                        <button type="submit" name="action" value="Cancel" id="delete-button" class="btn btn-success">Delete</button>
                    @endif
                    {{-- All users can download pdf --}}
                    <a href="{{ route('quarter.download-pdf', ['id' => $application->application_id]) }}" class="btn btn-info" target="_blank">Download</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Confirmation Modal for Submit -->
    <div id="confirmation-modal" class="modal-overlay">
        <div class="modal-content">
            <h3>Confirm Submission</h3>
            <p>Are you sure you want to submit this review? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button id="confirm-yes" class="btn btn-success">Yes, Submit</button>
                <button id="confirm-no" class="btn btn-danger">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Allocation -->
    <div id="allocation-modal" class="modal-overlay">
        <div class="modal-content">
            <h3>Confirm Allocation</h3>
            <p>Are you sure you want to allocate this quarter? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button id="allocation-yes" class="btn btn-success">Yes, Allocate</button>
                <button id="allocation-no" class="btn btn-danger">Cancel</button>
            </div>
        </div>
    </div>
@endsection
