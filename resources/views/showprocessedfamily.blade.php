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
            <!-- Add all other fields from family application in a read-only format -->
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
            <h3 class="form-section-title">B) Spouse Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>1. Marital Status:</label>
                    <p>{{ $application->familyQuarterApplication?->f_marital_status ?? 'N/A' }}</p>
                </div>
                <div class="form-group">
                    <label>2. Is your spouse employed in government service?</label>
                    <p>{{ isset($application->familyQuarterApplication?->f_is_spouse_employed) ? ($application->familyQuarterApplication?->f_is_spouse_employed ? 'Yes' : 'No') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <h3 class="form-section-title">C) Allocation Details</h3>
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
                            action="{{ route('quarter.cancelAllocation', ['id' => $application->application_id]) }}" method="POST"
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
                    @if(Auth::user()->hasPermissionTo('government_agent_approval') || Auth::user()->hasPermissionTo('additional_government_agent_approval') || Auth::user()->hasPermissionTo('administrative_officer_approval'))
                        <form id="reconsider-form"
                            action="{{ route('quarter.reconsider', ['id' => $application->application_id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                                <input type="hidden" name="ga_note" id="reconsider-note-input">
                            @elseif(Auth::user()->hasPermissionTo('additional_government_agent_approval'))
                                <input type="hidden" name="aga_note" id="reconsider-note-input">
                            @elseif(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                                <input type="hidden" name="ao_note" id="reconsider-note-input">
                            @endif
                            <button type="button" id="reconsider-button" class="btn btn-warning">Reconsider</button>
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
                    <textarea id="modal-cancel-note" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit;" rows="4" placeholder="Enter reason for cancellation..."></textarea>
                </div>`, [
                        {
                            text: 'Cancel Allocation',
                            class: 'btn',
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
                                        { text: 'Yes, Cancel', class: 'btn', onClick: () => cancelAllocationForm.submit() },
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

            // Reconsider Button Handler
            const reconsiderBtn = document.getElementById('reconsider-button');
            const reconsiderForm = document.getElementById('reconsider-form');
            const reconsiderNoteInput = document.getElementById('reconsider-note-input');

            if (reconsiderBtn) {
                reconsiderBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Show modal to collect note
                    showModal('Reconsider Application', `<div style="text-align: left; margin-bottom: 15px;">
                    <label for="modal-reconsider-note" style="font-weight: bold; color: #007bff; display: block; margin-bottom: 8px;">Reason for Reconsideration*</label>
                    <textarea id="modal-reconsider-note" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-family: inherit;" rows="4" placeholder="Enter reason for reconsideration..."></textarea>
                </div>`, [
                        {
                            text: 'Reconsider',
                            class: 'btn btn-warning',
                            onClick: () => {
                                const noteTextarea = document.getElementById('modal-reconsider-note');
                                const note = noteTextarea ? noteTextarea.value.trim() : '';

                                if (!note) {
                                    alert('Please provide a reason for reconsideration.');
                                    return;
                                }

                                hideModal();

                                // Set the note and show final confirmation
                                reconsiderNoteInput.value = note;

                                setTimeout(() => {
                                    showModal('Confirm Reconsideration', 'Are you sure you want to reconsider this application? The status will change to pending.', [
                                        { text: 'Yes, Reconsider', class: 'btn btn-warning', onClick: () => reconsiderForm.submit() },
                                        { text: 'Cancel', class: 'btn btn-secondary', onClick: hideModal }
                                    ]);
                                }, 100);
                            }
                        },
                        { text: 'Close', class: 'btn btn-secondary', onClick: hideModal }
                    ]);

                    // Focus on textarea after modal opens
                    setTimeout(() => {
                        const noteTextarea = document.getElementById('modal-reconsider-note');
                        if (noteTextarea) noteTextarea.focus();
                    }, 100);
                });
            }
        });
    </script>
@endpush