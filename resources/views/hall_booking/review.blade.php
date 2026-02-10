@extends('layouts.user_body_layout')

@section('title', 'Review Hall Booking Application - District Secretariat Vavuniya')

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; margin: 0 auto; text-align: left; margin-bottom: 20px;">
            <a href="{{ route('dashboard') }}" class="btn"
                style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; transition: background 0.3s;"
                onmouseover="this.style.backgroundColor='#5a6268'" onmouseout="this.style.backgroundColor='#6c757d'">
                &larr; Back to Dashboard
            </a>
        </div>

        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Review Hall Booking Application</h2>
            <p>Application ID: {{ $hallBooking->booking_id }}</p>
        </div>

        <div class="form-container"
            style="background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 900px; margin: 0 auto;">

            {{-- Display Application Details --}}
            <div class="details-section">
                <!-- Row 1 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Applicant Name</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->applicant_name }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Applicant Email</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->applicant_email ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Applicant Type</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->applicant_type }}
                        </p>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Hall Type</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->requested_hall_type ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Programme/Event</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->programme }}
                        </p>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Event Date</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_date }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Event Time</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_time }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Participants</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->participants }}
                        </p>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Duration (hours)</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_duration }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Paid Status</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->paid_status }}
                        </p>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Emergency Booking</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->is_emergency_booking ? 'Yes' : 'No' }}
                        </p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Requester NIC</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->filled_by_nic }}
                        </p>
                    </div>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            {{-- Action Buttons Logic --}}
            <div class="actions-section" style="text-align: right;">

                {{-- Download Button (Visible to everyone) --}}
                <a href="{{ route('hall_bookings.download', $hallBooking->booking_id) }}" class="btn"
                    style="background-color: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-right: 10px;">Download
                    PDF</a>

                {{-- Administrative Officer (AO) Logic --}}
                @if(Auth::user()->hasPermissionTo('administrative_officer_approval'))
                    @if($hallBooking->final_approval !== 'approved')
                        <div class="role-actions"
                            style="margin-top: 20px; border-top: 2px solid #28a745; padding-top: 20px; text-align: left;">
                            <h4>Administrative Officer Action</h4>
                            <form id="ao-action-form" method="POST" style="margin-top: 15px;">
                                @csrf
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="ao_decision"
                                        style="font-weight: bold; display: block; margin-bottom: 5px;">Decision:</label>
                                    <select id="ao_decision" name="decision" class="form-control"
                                        style="padding: 8px; width: 200px;">
                                        <option value="approve" {{ $hallBooking->administrative_officer_approved === 'approved' ? 'selected' : '' }}>Yes (Approve)</option>
                                        <option value="reject" {{ $hallBooking->administrative_officer_approved === 'rejected' ? 'selected' : '' }}>No (Reject)</option>
                                    </select>
                                </div>
                                <button type="button" onclick="submitDecision('ao')" class="btn btn-success"
                                    style="background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer;">Submit
                                    Decision</button>

                                {{-- AO Cancel Button --}}
                                @if($hallBooking->final_approval === 'pending')
                                    <button type="button" onclick="showCancelModal()" class="btn btn-danger"
                                        style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Cancel
                                        Booking</button>
                                @endif
                            </form>
                        </div>
                    @endif
                @endif

                {{-- Additional Government Agent (AGA) Logic --}}
                @if(Auth::user()->hasPermissionTo('additional_government_agent_approval'))
                    @if($hallBooking->final_approval !== 'approved')
                        <div class="role-actions"
                            style="margin-top: 20px; border-top: 2px solid #ffc107; padding-top: 20px; text-align: left;">
                            <h4>Additional Govt Agent Action</h4>
                            <form id="aga-action-form" method="POST" style="margin-top: 15px;">
                                @csrf
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="aga_decision"
                                        style="font-weight: bold; display: block; margin-bottom: 5px;">Decision:</label>
                                    <select id="aga_decision" name="decision" class="form-control"
                                        style="padding: 8px; width: 200px;">
                                        <option value="approve" {{ $hallBooking->additional_government_agent_approved === 'approved' ? 'selected' : '' }}>Yes (Approve)</option>
                                        <option value="reject" {{ $hallBooking->additional_government_agent_approved === 'rejected' ? 'selected' : '' }}>No (Reject)</option>
                                    </select>
                                </div>
                                <button type="button" onclick="submitDecision('aga')" class="btn btn-warning"
                                    style="background-color: #ffc107; color: black; padding: 10px 20px; border: none; cursor: pointer;">Submit
                                    Decision</button>
                            </form>
                        </div>
                    @endif
                @endif

                {{-- Government Agent (GA) Logic --}}
                @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                    @if($hallBooking->final_approval !== 'approved')
                        <div class="role-actions"
                            style="margin-top: 20px; border-top: 2px solid #007bff; padding-top: 20px; text-align: left;">
                            <h4>Government Agent Action (Final)</h4>
                            <form id="ga-action-form" method="POST" style="margin-top: 15px;">
                                @csrf
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="ga_decision" style="font-weight: bold; display: block; margin-bottom: 5px;">Final
                                        Decision:</label>
                                    <select id="ga_decision" name="decision" class="form-control" onchange="toggleRejectionReason()"
                                        style="padding: 8px; width: 200px;">
                                        <option value="approve">Yes (Approve)</option>
                                        <option value="reject">No (Reject)</option>
                                    </select>
                                </div>
                                <div id="rejection-reason-container" style="margin-bottom: 15px; display: none;">
                                    <label for="rejection_reason"
                                        style="font-weight: bold; display: block; margin-bottom: 5px;">Rejection Reason:</label>
                                    <textarea id="rejection_reason" name="rejection_reason" rows="3"
                                        style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; resize: vertical;"></textarea>
                                </div>
                                <button type="button" onclick="submitDecision('ga')" class="btn btn-primary"
                                    style="background-color: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">Finalize
                                    Booking</button>
                            </form>
                        </div>
                    @endif
                @endif

                {{-- Requester (PA) Logic --}}
                @if(Auth::user()->hasPermissionTo('requester'))
                    @if($hallBooking->administrative_officer_approved === 'pending' && $hallBooking->additional_government_agent_approved === 'pending' && $hallBooking->final_approval === 'pending')
                        {{-- Additional check: must be owner (usually handled by auth middleware/view access, but good to be safe if
                        passed) --}}
                        @if($hallBooking->filled_by_nic === Auth::user()->nic_number)
                            <button type="button" onclick="showCancelModal()" class="btn btn-danger"
                                style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Cancel
                                Booking</button>
                        @endif
                    @endif
                @endif

            </div>
        </div>

        {{-- Generic Confirmation Modal --}}
        <div id="confirm-modal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; width: 90%; text-align: center;">
                <h3 id="confirm-modal-title" style="margin-bottom: 15px;">Confirm Action</h3>
                <p id="confirm-modal-message" style="margin-bottom: 20px; color: #333;"></p>
                <div style="margin-top: 20px;">
                    <button id="confirm-modal-yes-btn" class="btn btn-primary"
                        style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Yes</button>
                    <button onclick="closeConfirmModal()" class="btn btn-secondary"
                        style="background-color: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">No</button>
                </div>
            </div>
        </div>

        {{-- Cancel Confirmation Modal --}}
        <div id="cancel-modal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; width: 90%; text-align: center;">
                <h3>Confirm Cancellation</h3>
                <p>Are you sure you want to cancel this booking?</p>
                @if(Auth::user()->hasPermissionTo('government_agent_approval') || Auth::user()->hasPermissionTo('administrative_officer_approval'))
                    <textarea id="cancel_reason" placeholder="Reason for cancellation (optional)"
                        style="width: 100%; margin: 10px 0; padding: 8px; resize: vertical;"></textarea>
                @endif
                <div style="margin-top: 20px;">
                    <button onclick="confirmCancel()" class="btn btn-danger"
                        style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Yes,
                        Cancel</button>
                    <button onclick="closeCancelModal()" class="btn btn-secondary"
                        style="background-color: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">No,
                        Keep It</button>
                </div>
            </div>
        </div>

        {{-- Generic Info/Success/Error Modal --}}
        <div id="info-modal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; width: 90%; text-align: center;">
                <h3 id="info-modal-title" style="margin-bottom: 15px;">Info</h3>
                <p id="info-modal-message" style="margin-bottom: 20px; color: #333;"></p>
                <button id="info-modal-btn" class="btn btn-primary"
                    style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">OK</button>
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
                <p style="margin-top: 10px; color: #666;">Please wait while we update the booking status.</p>
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

    <script>
        // ... (Previous modal variables)

        function showProcessingOverlay() {
            document.getElementById('processing-overlay').style.display = 'flex';
        }

        function hideProcessingOverlay() {
            document.getElementById('processing-overlay').style.display = 'none';
        }

        function showInfoModal(message, title = 'Info', redirectUrl = null, type = 'info') {
            // ... (Existing implementation)
            document.getElementById('info-modal-title').textContent = title;
            document.getElementById('info-modal-message').textContent = message;

            const titleElem = document.getElementById('info-modal-title');
            const btnElem = document.getElementById('info-modal-btn');

            if (type === 'error') {
                titleElem.style.color = '#dc3545';
                btnElem.style.backgroundColor = '#dc3545';
            } else if (type === 'success') {
                titleElem.style.color = '#28a745';
                btnElem.style.backgroundColor = '#28a745';
            } else {
                titleElem.style.color = '#007bff';
                btnElem.style.backgroundColor = '#007bff';
            }

            document.getElementById('info-modal').style.display = 'flex';

            document.getElementById('info-modal-btn').onclick = function () {
                document.getElementById('info-modal').style.display = 'none';
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            };
        }

        function showConfirmModal(message, onConfirm) {
            document.getElementById('confirm-modal-message').textContent = message;
            document.getElementById('confirm-modal').style.display = 'flex';

            // Set up the Yes button
            const yesBtn = document.getElementById('confirm-modal-yes-btn');
            // Clone to remove previous event listeners
            const newYesBtn = yesBtn.cloneNode(true);
            yesBtn.parentNode.replaceChild(newYesBtn, yesBtn);

            newYesBtn.addEventListener('click', function () {
                closeConfirmModal();
                if (onConfirm) onConfirm();
            });
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').style.display = 'none';
        }

        function toggleRejectionReason() {
            // ... existing ...
            const decision = document.getElementById('ga_decision').value;
            const reasonContainer = document.getElementById('rejection-reason-container');
            if (decision === 'reject') {
                reasonContainer.style.display = 'block';
            } else {
                reasonContainer.style.display = 'none';
            }
        }

        function submitDecision(role) {
            // ... existing ...
            let decision = '';
            let url = '';
            let reason = '';

            if (role === 'ao') {
                decision = document.getElementById('ao_decision').value;
                url = decision === 'approve' ? "{{ route('hall_bookings.approve', $hallBooking->booking_id) }}" : "{{ route('hall_bookings.reject', $hallBooking->booking_id) }}";
            } else if (role === 'aga') {
                decision = document.getElementById('aga_decision').value;
                url = decision === 'approve' ? "{{ route('hall_bookings.approve', $hallBooking->booking_id) }}" : "{{ route('hall_bookings.reject', $hallBooking->booking_id) }}";
            } else if (role === 'ga') {
                decision = document.getElementById('ga_decision').value;
                url = decision === 'approve' ? "{{ route('hall_bookings.approve', $hallBooking->booking_id) }}" : "{{ route('hall_bookings.reject', $hallBooking->booking_id) }}";
                if (decision === 'reject') {
                    reason = document.getElementById('rejection_reason').value;
                    if (!reason.trim()) {
                        showInfoModal('Please provide a rejection reason.', 'Validation Error', null, 'error');
                        return;
                    }
                }
            }

            // Show Custom Confirmation Modal
            const confirmMsg = 'Are you sure you want to ' + decision + ' this booking?';
            showConfirmModal(confirmMsg, function () {
                performSubmission(url, reason);
            });
        }

        function performSubmission(url, reason) {
            // ... existing ...
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            if (reason) formData.append('rejection_reason', reason);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showInfoModal(data.message || 'Action completed successfully.', 'Success', "{{ route('dashboard') }}", 'success');
                    } else {
                        showInfoModal('Error: ' + data.message, 'Error', null, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showInfoModal('An error occurred during the request.', 'System Error', null, 'error');
                });
        }

        function showCancelModal() {
            document.getElementById('cancel-modal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal').style.display = 'none';
        }

        function confirmCancel() {
            closeCancelModal(); // Identify change: Close modal immediately before processing starts
            showProcessingOverlay(); // Show spinner

            const reason = document.getElementById('cancel_reason') ? document.getElementById('cancel_reason').value : '';

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            if (reason) formData.append('reason', reason);

            // Determine correct route (using cancel-approved for comprehensive cancellation as implemented in controller)
            fetch("{{ route('hall_bookings.cancelApproved', $hallBooking->booking_id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    hideProcessingOverlay(); // Hide spinner

                    if (data.success) {
                        showInfoModal('Booking cancelled successfully.', 'Cancelled', "{{ route('dashboard') }}", 'success');
                    } else {
                        showInfoModal('Error: ' + data.message, 'Cancellation Failed', null, 'error');
                    }
                })
                .catch(error => {
                    hideProcessingOverlay(); // Hide spinner
                    console.error('Error:', error);
                    showInfoModal('An error occurred. Please try again.', 'Error', null, 'error');
                });
        }
    </script>
@endsection