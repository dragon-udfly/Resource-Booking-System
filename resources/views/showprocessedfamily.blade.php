@extends('layouts.user_body_layout')

@section('title', 'Processed Family Quarter Application - District Secretariat Vavuniya')

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

        .back-btn {
            background-color: #007bff;
        }

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
            margin: 0;
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

        .status-allocated {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .status-rejected {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .status-cancelled {
            color: #383d41;
            background-color: #e2e3e5;
            border-color: #d6d8db;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 450px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-content h3 {
            margin-top: 0;
            color: #333;
        }

        .modal-content p {
            margin-bottom: 20px;
            color: #555;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="{{ route('history') }}" class="btn back-btn">Back to History</a>
        </div>

        <div class="page-header">
            <h2>Processed Family Quarter Application</h2>
        </div>

        <div class="form-container">
            <h3 class="form-section-title">A) Officer Details</h3>
            <div class="form-row">
                <div class="form-group"><label>1. Name of Officer:</label>
                    <p>{{ $application->officer_name ?? 'N/A' }}</p>
                </div>
                <div class="form-group"><label>2. NIC Number:</label>
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
                    <label>9. Monthly Salary (excluding allowances):</label>
                    <p>{{ $application->monthly_salary ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>10. Telephone Number:</label>
                    <p>{{ $application->phone_number ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>11. Email Address:</label>
                    <p>{{ $application->email ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>12. Date of Assumption of Duties in Vavuniya:</label>
                    <p>{{ $application->date_of_assumption_of_duties ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>13. Date of Last Salary Increment:</label>
                    <p>{{ $application->familyQuarterApplication?->f_date_of_last_salary_increment ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>14. Is applicant a transferred officer?</label>
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
                    <label>3. Spouse's Designation:</label>
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
                    <label>Total Mark:</label>
                    <p>{{ $application->familyQuarterApplication?->markingFamilyQuarter?->total_mark ?? 'N/A' }}</p>
                </div>
            </div>

            <table class="marking-table" style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #dee2e6; padding: 8px 12px; background-color: #e9ecef; font-weight: bold; color: #495057;">Criteria</th>
                        <th style="border: 1px solid #dee2e6; padding: 8px 12px; background-color: #e9ecef; font-weight: bold; color: #495057;">Selection</th>
                        <th style="border: 1px solid #dee2e6; padding: 8px 12px; background-color: #e9ecef; font-weight: bold; color: #495057;">Mark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">Applicant's Department</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department ?? 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_department_mark ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">Number of Dependant</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant ?? 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_number_of_dependant_mark ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">Dependant(s) with Disability</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ isset($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability) ? ($application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability ? 'Yes' : 'No') : 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->is_dependant_with_disability_mark ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">Distance of Residency</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency ?? 'N/A' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_distance_of_residency_mark ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">Special Reasons (Provided By Government Agent):</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason ?? 'Not Mentioned' }}</td>
                        <td style="border: 1px solid #dee2e6; padding: 8px 12px;">{{ $application->familyQuarterApplication?->markingFamilyQuarter?->f_special_reason_mark ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="form-section-title">G) Allocation Process Details</h3>
            <div class="form-row">
                <div class="form-group"><label>Monthly Salary:</label><p>{{ number_format($application->monthly_salary, 2) ?? 'N/A' }}</p></div>
                <div class="form-group"><label>Applicant Grade (Service):</label><p>{{ $application->service_grade ?? 'N/A' }}</p></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Grade (Calculated):</label><p>{{ $calculatedGrade ?? 'N/A' }}</p></div>
                <div class="form-group"><label>Gender:</label><p>{{ $application->gender ?? 'N/A' }}</p></div>
            </div>

            @php
                $allocation = $application->quarterAllocation;
            @endphp

            <h3 class="form-section-title">H) Quarter Information</h3>
            @if($allocation && $allocation->allocation_status == 'allocated' && $allocation->quarter)
                <div class="form-row">
                    <div class="form-group">
                        <label>Allocated Quarter No (New):</label>
                        <p>{{ $allocation->quarter->new_quarter_no ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label>Allocated Quarter No (Old):</label>
                        <p>{{ $allocation->quarter->old_quarter_no ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Quarter Location:</label>
                        <p>{{ $allocation->quarter->location ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label>Quarter Type:</label>
                        <p>{{ $allocation->quarter->quarter_type ?? 'N/A' }}</p>
                    </div>
                </div>
            @else
                <div class="form-row">
                    <div class="form-group">
                        <label>Quarter Status:</label>
                        <p>No quarter has been allocated yet</p>
                    </div>
                </div>
            @endif

            <h3 class="form-section-title">I) Review and Allocation Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Administrative Officer Verification:</label>
                    <p>{{ isset($allocation->ao_verified_status) ? ($allocation->ao_verified_status ? 'Verified' : 'Not Verified') : 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>AO Note:</label>
                    <p>{{ $allocation->ao_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Additional Government Agent Verification:</label>
                    <p>{{ isset($allocation->aga_verified_status) ? ($allocation->aga_verified_status ? 'Verified' : 'Not Verified') : 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>AGA Note:</label>
                    <p>{{ $allocation->aga_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Government Agent Approval:</label>
                    <p>{{ isset($allocation->ga_approval_status) ? ($allocation->ga_approval_status ? 'Approved' : 'Not Approved') : 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>GA Note:</label>
                    <p>{{ $allocation->ga_note ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h3 class="form-section-title">J) Final Allocation Status</h3>
            @php
                $allocation = $application->quarterAllocation;
                $statusClass = '';
                if ($allocation->allocation_status == 'allocated')
                    $statusClass = 'status-allocated';
                if ($allocation->allocation_status == 'rejected')
                    $statusClass = 'status-rejected';
                if ($allocation->allocation_status == 'cancelled')
                    $statusClass = 'status-cancelled';
            @endphp
            <div class="form-row">
                <div class="form-group">
                    <label>Final Allocation Status:</label>
                    <p class="{{ $statusClass }}" style="font-weight: bold; text-transform: capitalize;">
                        {{ $allocation->allocation_status ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Allocation/Rejection Date:</label>
                    <p>{{ $allocation->updated_at ? $allocation->updated_at->format('Y-m-d') : 'N/A' }}</p>
                </div>
            </div>

            @if($allocation->allocation_status == 'allocated')
                <div class="form-row">
                    <div class="form-group">
                        <label>Allocated Quarter No (New):</label>
                        <p>{{ $allocation->quarter->new_quarter_no ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label>Allocated Quarter Location:</label>
                        <p>{{ $allocation->quarter->location ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Allocation Date:</label>
                        <p>{{ $allocation->allocation_date ? \Carbon\Carbon::parse($allocation->allocation_date)->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label>Expected Vacate Date:</label>
                        <p>{{ $allocation->vacate_date ? \Carbon\Carbon::parse($allocation->vacate_date)->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label>Administrative Officer Note:</label>
                    <p>{{ $allocation->ao_note ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>Additional Government Agent Note:</label>
                    <p>{{ $allocation->aga_note ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Government Agent Note:</label>
                    <p>{{ $allocation->ga_note ?? 'N/A' }}</p>
                </div>
            </div>


            {{-- Action Buttons --}}
            <div class="button-group">
                {{-- Cancel Allocation (GA Only, Allocated Status) --}}
                @if($allocation && $allocation->allocation_status == 'allocated')
                    @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                        <form id="cancel-allocation-form"
                            action="{{ route('family-quarter.cancel', ['id' => $application->application_id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <input type="hidden" name="ga_note" id="cancel-ga-note-input">
                            <button type="button" id="cancel-allocation-button" class="btn"
                                style="background-color: #dc3545; color: white;">Cancel Allocation</button>
                        </form>
                    @endif
                @endif

                {{-- Reconsider (GA/AGA/AO, Allocated or Rejected Status) --}}
                @if($allocation && in_array($allocation->allocation_status, ['allocated', 'rejected']))
                @endif

                {{-- Restore (GA, AGA, AO Only, Rejected Status) --}}
                @if($allocation && $allocation->allocation_status == 'rejected')
                    @if(Auth::user()->hasPermissionTo('government_agent_approval') || 
                        Auth::user()->hasPermissionTo('additional_government_agent_approval') || 
                        Auth::user()->hasPermissionTo('administrative_officer_approval'))
                        <form id="restore-form"
                            action="{{ route('family-quarter.restore', ['id' => $application->application_id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <input type="hidden" name="restore_note" id="restore-note-input">
                            <button type="button" id="restore-button" class="btn btn-warning">Restore to Pending</button>
                        </form>
                    @endif
                @endif

                <a href="{{ route('quarter.download-pdf', ['id' => $application->application_id]) }}" class="btn btn-info"
                    target="_blank">Download</a>
            </div>
        </div>

        {{-- Modal Overlay --}}
        <div id="modal-overlay" class="modal-overlay">
            <div class="modal-content">
                <h3 id="modal-title"></h3>
                <p id="modal-message"></p>
                <div id="modal-buttons" class="modal-buttons">
                    <!-- Buttons will be injected by JavaScript -->
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal Logic
            const modalOverlay = document.getElementById('modal-overlay');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const modalButtons = document.getElementById('modal-buttons');

            const showModal = (title, message, buttons) => {
                modalTitle.textContent = title;
                modalMessage.innerHTML = message;
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

            // Cancel Allocation Button Handler
            const cancelAllocationBtn = document.getElementById('cancel-allocation-button');
            const cancelAllocationForm = document.getElementById('cancel-allocation-form');
            const cancelGaNoteInput = document.getElementById('cancel-ga-note-input');

            if (cancelAllocationBtn) {
                cancelAllocationBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Show modal to collect note
                    showModal('Cancel Allocation', `<div style="text-align: left; margin-bottom: 15px;">
                    <label for="modal-cancel-note" style="font-weight: bold; color: #dc3545; display: block; margin-bottom: 8px;">Reason for Cancellation (GA Note)*</label>
                    <textarea id="modal-cancel-note" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit; resize: vertical;" rows="4" placeholder="Enter reason for cancellation..."></textarea>
                </div>`, [
                        {
                            text: 'Cancel Allocation',
                            class: 'btn btn-danger', // Added btn-danger for visibility
                            onClick: () => {
                                const noteTextarea = document.getElementById('modal-cancel-note');
                                const note = noteTextarea ? noteTextarea.value.trim() : '';

                                if (!note) {
                                    alert('Please provide a reason for cancellation.');
                                    return;
                                }

                                hideModal();

                                // Set the note and show final confirmation
                                cancelGaNoteInput.value = note;

                                setTimeout(() => {
                                    showModal('Confirm Cancellation', 'Are you sure you want to cancel this allocation? The status will change to rejected.', [
                                        { text: 'Yes, Cancel', class: 'btn btn-danger', onClick: () => cancelAllocationForm.submit() }, // Added btn-danger here too
                                        { text: 'No', class: 'btn btn-secondary', onClick: hideModal }
                                    ]);
                                }, 100);
                            }
                        },
                        { text: 'Close', class: 'btn btn-secondary', onClick: hideModal }
                    ]);

                    // Focus on textarea after modal opens
                    setTimeout(() => {
                        const noteTextarea = document.getElementById('modal-cancel-note');
                        if (noteTextarea) noteTextarea.focus();
                    }, 100);
                });
            }


            }


            // Restore Button Handler
            const restoreBtn = document.getElementById('restore-button');
            const restoreForm = document.getElementById('restore-form');
            const restoreNoteInput = document.getElementById('restore-note-input');

            if (restoreBtn) {
                restoreBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    showModal('Restore Application', `<div style="text-align: left; margin-bottom: 15px;">
                        <label for="modal-restore-note" style="font-weight: bold; color: #856404; display: block; margin-bottom: 8px;">Reason for Restoration (Mandatory)*</label>
                        <textarea id="modal-restore-note" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit; resize: vertical;" rows="4" placeholder="Enter reason for restoring application..."></textarea>
                    </div>`, [
                        {
                            text: 'Restore Application',
                            class: 'btn btn-warning',
                            onClick: () => {
                                const noteTextarea = document.getElementById('modal-restore-note');
                                const note = noteTextarea ? noteTextarea.value.trim() : '';

                                if (!note) {
                                    alert('Please provide a reason for restoration.');
                                    return;
                                }

                                hideModal();

                                // Set the note and submit
                                restoreNoteInput.value = note;
                                restoreForm.submit();
                            }
                        },
                        { text: 'Close', class: 'btn btn-secondary', onClick: hideModal }
                    ]);

                    // Focus on textarea
                    setTimeout(() => {
                        const noteTextarea = document.getElementById('modal-restore-note');
                        if (noteTextarea) noteTextarea.focus();
                    }, 100);
                });
            }

        });
    </script>
@endpush