@extends('layouts.user_body_layout')

@section('title', 'Dashboard - District Secretariat Vavuniya')


@section('content')
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        @if(Auth::user()->hasPermissionTo('requester'))
            @include('partials.requester_dashboard_layout', ['requesterBookings' => $requesterBookings, 'quarterApplications' => $quarterApplications])
        @else
            <div class="page-header">
                <h2 style="color: rgb(6, 4, 60); font-weight: bold">Pending Booking Approvals</h2>
                <p>Review the pending applications.</p>
            </div>
            <h2 style="text-align: center; color:rgb(34, 60, 4)">Hall Booking Applications</h2>
            <table id="approval-details">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Submitted Date</th>
                        <th>AO Approval</th>
                        <th>AGA Approval</th>
                        <th>GA Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr data-booking='{{ json_encode($booking) }}' @if($booking->is_emergency_booking) style="background-color: yellow;" @endif>
                            <td>{{ $booking->applicant_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date_created)->format('Y-m-d h:i A') }}</td>
                            <td>{{ ucfirst($booking->administrative_officer_approved) }}</td>
                            <td>{{ ucfirst($booking->additional_government_agent_approved) }}</td>
                            <td>{{ ucfirst($booking->government_agent_approved) }}</td>
                            <td>
                                <button class="action-btn review-btn" data-booking-id="{{ $booking->booking_id }}">Review</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 12px 15px;">No pending bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <br /> 
            <br />
            <h2 style="text-align: center; color:rgb(34, 60, 4)">Quarters Reservation Applications</h2>
            <table id="quarter-approval-details">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Designation</th>
                        <th>Submitted Date</th>
                        <th>Type</th>
                        <th>AO Verification</th>
                        <th>AGA Verification</th>
                        <th>GA Approval</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quarterApplications as $application)
                        <tr data-quarter-type="{{ $application->quarter_type }}">
                            <td>{{ $application->officer_name }}</td>
                            <td>{{ $application->designation }}</td>
                            <td>{{ \Carbon\Carbon::parse($application->date_created)->format('Y-m-d h:i A') }}</td>
                            <td>{{ $application->quarter_type }}</td>
                            <td>
                                @if($application->quarterAllocation)
                                    @if($application->quarterAllocation->is_ao_verified === 1)
                                        <span style="color: green; font-weight: bold;">Yes</span>
                                    @elseif($application->quarterAllocation->is_ao_verified === 0)
                                        <span style="color: red; font-weight: bold;">No</span>
                                    @else
                                        <span style="color: gray;">Pending</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($application->quarterAllocation)
                                    @if($application->quarterAllocation->is_aga_verified === 1)
                                        <span style="color: green; font-weight: bold;">Yes</span>
                                    @elseif($application->quarterAllocation->is_aga_verified === 0)
                                        <span style="color: red; font-weight: bold;">No</span>
                                    @else
                                        <span style="color: gray;">Pending</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $application->quarterAllocation ? ucfirst($application->quarterAllocation->allocation_status) : 'N/A' }}</td>
                            <td>
                                <button class="action-btn review-quarter-btn" data-application-id="{{ $application->application_id }}">Review</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 12px 15px;">No pending quarter applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Approver Review Overlay --}}
            <div id="approver-review-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1001; justify-content: center; align-items: center;">
                <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 800px; width: 90%; position: relative;">
                    <button id="approver-back-btn" style="position: absolute; top: 10px; left: 10px; background-color: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">Back</button>
                    <h3 style="text-align: center; margin-bottom: 20px;">Review Hall Booking Application</h3>
                    <div id="approver-form-content"></div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                        <button type="button" id="approver-approve-btn" class="action-btn approve" style="background-color: #28a745; color: white; padding: 10px 20px;">Approve</button>
                        <button type="button" id="approver-reject-btn" class="action-btn reject" style="background-color: #dc3545; color: white; padding: 10px 20px;">Reject</button>
                        <button type="button" id="approver-download-btn" class="action-btn" style="background-color: #29f00f; color: white; padding: 10px 20px;">Download</button>
                    </div>
                </div>
            </div>

            {{-- Global Confirmation Overlay --}}
            <div id="global-confirmation-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1002; justify-content: center; align-items: center;">
                <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 400px; text-align: center;">
                    <p id="global-confirmation-message" style="font-size: 1.2em; color: #333; margin-bottom: 20px;"></p>
                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <button id="global-confirm-btn" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">Confirm</button>
                        <button id="global-cancel-btn" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">Cancel</button>
                    </div>
                </div>
            </div>

            <style>
                #approver-review-overlay, #global-confirmation-overlay {
                    display: flex;
                }
                #approver-form-content .form-row {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                    margin-bottom: 15px;
                }
                #approver-form-content .form-group {
                    flex: 1;
                    min-width: 250px;
                }
                #approver-form-content label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                    color: #555;
                    font-size: 0.9em;
                }
                #approver-form-content p {
                    padding: 8px 10px;
                    background-color: #f8f9fa;
                    border: 1px solid #ced4da;
                    border-radius: 4px;
                    min-height: 38px;
                }
                /* Styles for review buttons */
                .action-btn.review-btn,
                .action-btn.review-quarter-btn {
                    display: inline-block;
                    padding: 8px 12px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    color: white;
                    font-size: 0.9em;
                    transition: background-color 0.3s ease;
                    background-color: #007bff; /* Example background color */
                }
                .action-btn.review-btn:hover,
                .action-btn.review-quarter-btn:hover {
                    background-color: #0056b3; /* Example hover color */
                }
            </style>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const approverReviewOverlay = document.getElementById('approver-review-overlay');
                    const approverBackBtn = document.getElementById('approver-back-btn');
                    const approverFormContent = document.getElementById('approver-form-content');
                    const approverApproveBtn = document.getElementById('approver-approve-btn');
                    const approverRejectBtn = document.getElementById('approver-reject-btn');
                    const approverDownloadBtn = document.getElementById('approver-download-btn');

                    const globalConfirmationOverlay = document.getElementById('global-confirmation-overlay');
                    const globalConfirmationMessage = document.getElementById('global-confirmation-message');
                    const globalConfirmBtn = document.getElementById('global-confirm-btn');
                    const globalCancelBtn = document.getElementById('global-cancel-btn');

                    let currentBookingId = null;
                    let confirmActionCallback = null;

                    function showGlobalConfirmation(message, callback) {
                        globalConfirmationMessage.textContent = message;
                        globalConfirmBtn.textContent = 'Confirm';
                        globalConfirmBtn.style.display = 'inline-block';
                        globalCancelBtn.style.display = 'inline-block';
                        globalConfirmationOverlay.style.display = 'flex';
                        confirmActionCallback = callback;
                    }

                    function showInfoOverlay(message) {
                        globalConfirmationMessage.textContent = message;
                        globalConfirmBtn.textContent = 'OK';
                        globalConfirmBtn.style.display = 'inline-block';
                        globalCancelBtn.style.display = 'none';
                        globalConfirmationOverlay.style.display = 'flex';
                        confirmActionCallback = null;
                    }

                    function hideGlobalConfirmation() {
                        globalConfirmationOverlay.style.display = 'none';
                        globalConfirmationMessage.textContent = '';
                        confirmActionCallback = null;
                    }

                    globalConfirmBtn.addEventListener('click', function() {
                        if (confirmActionCallback) confirmActionCallback();
                        hideGlobalConfirmation();
                    });
                    globalCancelBtn.addEventListener('click', hideGlobalConfirmation);

                    function renderApproverFields(booking) {
                        currentBookingId = booking.booking_id;
                        let fieldsHtml = `
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Applicant Name</label>
                                    <p>${booking.applicant_name}</p>
                                </div>
                                <div class="form-group">
                                    <label>Applicant Type</label>
                                    <p>${booking.applicant_type}</p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Hall Type</label>
                                    <p>${booking.requested_hall_type || 'N/A'}</p>
                                </div>
                                <div class="form-group">
                                    <label>Programme/Event</label>
                                    <p>${booking.programme}</p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Event Date</label>
                                    <p>${booking.event_date}</p>
                                </div>
                                <div class="form-group">
                                    <label>Event Time</label>
                                    <p>${booking.event_time}</p>
                                </div>
                                <div class="form-group">
                                    <label>Participants</label>
                                    <p>${booking.participants}</p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Duration (hours)</label>
                                    <p>${booking.event_duration}</p>
                                </div>
                                <div class="form-group">
                                    <label>Paid Status</label>
                                    <p>${booking.paid_status}</p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Emergency Booking</label>
                                    <p>${booking.is_emergency_booking ? 'Yes' : 'No'}</p>
                                </div>
                                <div class="form-group">
                                    <label>Requester NIC</label>
                                    <p>${booking.filled_by_nic}</p>
                                </div>
                            </div>
                        `;
                        approverFormContent.innerHTML = fieldsHtml;
                    }

                    document.querySelectorAll('.review-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const row = this.closest('tr');
                            const bookingData = JSON.parse(row.dataset.booking);
                            renderApproverFields(bookingData);
                            approverReviewOverlay.style.display = 'flex';
                        });
                    });

                    // Handle review button clicks for quarter applications
                    document.querySelectorAll('.review-quarter-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const applicationId = this.dataset.applicationId;
                            const quarterType = this.closest('tr').dataset.quarterType;

                            if (applicationId && quarterType) {
                                // Normalize the quarter type to handle potential case sensitivity or whitespace issues
                                const normalizedQuarterType = quarterType.trim().toLowerCase();

                                if (normalizedQuarterType === 'family') {
                                    window.location.href = `/family-quarter-application/${applicationId}/review`;
                                } else if (normalizedQuarterType === 'scheduled') {
                                    window.location.href = `/scheduled-quarter-application/${applicationId}/review`;
                                } else {
                                    console.error('Unknown quarter type:', quarterType);
                                    alert('Unknown quarter type (' + quarterType + '). Cannot proceed with review.');
                                }
                            } else {
                                console.error('Missing application ID or quarter type');
                                alert('Application ID or quarter type is missing. Cannot proceed with review.');
                            }
                        });
                    });

                    approverBackBtn.addEventListener('click', () => approverReviewOverlay.style.display = 'none');

                    approverApproveBtn.addEventListener('click', function() {
                        showGlobalConfirmation('Are you sure you want to approve this booking?', function() {
                            fetch(`/hall-bookings/${currentBookingId}/approve`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    showInfoOverlay(result.message);
                                    location.reload();
                                } else {
                                    showInfoOverlay('Error: ' + result.message);
                                }
                            });
                        });
                    });

                    approverRejectBtn.addEventListener('click', function() {
                        showGlobalConfirmation('Are you sure you want to reject this booking?', function() {
                            fetch(`/hall-bookings/${currentBookingId}/reject`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (result.success) {
                                    showInfoOverlay(result.message);
                                    location.reload();
                                } else {
                                    showInfoOverlay('Error: ' + result.message);
                                }
                            });
                        });
                    });

                    approverDownloadBtn.addEventListener('click', function() {
                        if (currentBookingId) {
                            window.location.href = `/hall-bookings/${currentBookingId}/download`;
                        }
                    });
                });
            </script>
            @endpush
        @endif
    </section>
@endsection
