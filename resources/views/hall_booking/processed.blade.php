@extends('layouts.user_body_layout')

@section('title', 'Processed Hall Booking Application - District Secretariat Vavuniya')

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="{{ route('history') }}" class="btn btn-secondary"
                style="background-color: #6c757d; color: white;">Back to History</a>
        </div>

        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Processed Application Details</h2>
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
                            {{ $hallBooking->applicant_name }}</p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Applicant Type</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->applicant_type }}</p>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Hall Type</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->requested_hall_type ?? ($hallBooking->hall->hall_type ?? 'N/A') }}</p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Programme/Event</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->programme }}</p>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Event Date</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_date }}</p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Event Time</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_time }}</p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Participants</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->participants }}</p>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Duration (hours)</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->event_duration }}</p>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Paid Status</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->paid_status }}</p>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Final Status</label>
                         <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; font-weight: bold; color: {{ $hallBooking->final_approval == 'approved' ? 'green' : ($hallBooking->final_approval == 'rejected' ? 'red' : 'grey') }}">
                            {{ ucfirst($hallBooking->final_approval) }}</p>
                    </div>
                     <div class="form-group" style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Requester NIC</label>
                        <p style="padding: 10px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px;">
                            {{ $hallBooking->filled_by_nic }}</p>
                    </div>
                </div>

                @if($hallBooking->reason_of_rejection)
                <div class="form-row" style="margin-bottom: 15px;">
                     <div class="form-group" style="width: 100%;">
                        <label style="font-weight: bold; color: #555;">Reason of Rejection/Cancellation</label>
                        <p style="padding: 10px; background-color: #fff3f3; border: 1px solid #ffced4; border-radius: 4px; color: #721c24;">
                            {{ $hallBooking->reason_of_rejection }}</p>
                    </div>
                </div>
                @endif
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            {{-- Action Buttons Logic --}}
            <div class="actions-section" style="text-align: right;">

                <a href="{{ route('hall_bookings.download', $hallBooking->booking_id) }}" class="btn"
                    style="background-color: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-right: 10px;" target="_blank">Download PDF</a>

                {{-- Delete logic --}}
                @php
                    $today = \Carbon\Carbon::today();
                    $eventDate = \Carbon\Carbon::parse($hallBooking->event_date);
                    $isPast = $eventDate < $today;
                    
                    $user = Auth::user();
                    $isAO = $user->hasPermissionTo('administrative_officer_approval');
                    $canDelete = false;

                    if ($isPast) {
                        $canDelete = true;
                    } elseif ($isAO) {
                         // AO Rule: Final Status SET and GA Pending
                         $finalStatusSet = $hallBooking->final_approval !== 'pending';
                         $gaPending = $hallBooking->government_agent_approved === 'pending';
                         if ($finalStatusSet && $gaPending) {
                             $canDelete = true;
                         }
                    }
                @endphp
                @if($canDelete)
                    <button onclick="showDeleteModal()" class="btn btn-danger"
                        style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Delete Record</button>
                @endif

                {{-- GA Actions: Cancel or Re-approve --}}
                @if(Auth::user()->hasPermissionTo('government_agent_approval'))
                    @if($hallBooking->final_approval === 'approved')
                        <button onclick="showCancelModal()" class="btn btn-warning"
                             style="background-color: #ffc107; color: black; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Cancel Booking</button>
                    @elseif($hallBooking->final_approval === 'cancelled')
                        <button onclick="confirmReApprove()" class="btn btn-success"
                             style="background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; margin-left: 10px;">Re-approve Booking</button>
                    @endif
                @endif

            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="delete-modal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; width: 90%; text-align: center;">
                <h3 style="color: #dc3545; margin-bottom: 15px;">Confirm Deletion</h3>
                <p style="margin-bottom: 20px;">Are you sure you want to permanently delete this booking record? This action cannot be undone.</p>
                <div style="margin-top: 20px;">
                    <button onclick="performDelete()" class="btn btn-danger"
                        style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Yes, Delete</button>
                    <button onclick="closeDeleteModal()" class="btn btn-secondary"
                        style="background-color: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Cancel</button>
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
                <textarea id="cancel_reason" placeholder="Reason for cancellation (required)"
                        style="width: 100%; margin: 10px 0; padding: 8px;"></textarea>
                <div style="margin-top: 20px;">
                    <button onclick="performCancel()" class="btn btn-danger"
                        style="background-color: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Yes, Cancel</button>
                    <button onclick="closeCancelModal()" class="btn btn-secondary"
                        style="background-color: #6c757d; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">No, Keep It</button>
                </div>
            </div>
        </div>

        {{-- Success/Info Modal --}}
        <div id="success-modal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; width: 90%; text-align: center;">
                <h3 id="success-modal-title" style="color: #28a745; margin-bottom: 15px;">Success</h3>
                <p id="success-modal-message" style="margin-bottom: 20px; color: #333;">Operation successful.</p>
                <button id="success-modal-btn" class="btn btn-primary"
                    style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">OK</button>
            </div>
        </div>

    </section>

    <script>
        function showSuccessModal(message, redirectUrl) {
            document.getElementById('success-modal-message').textContent = message;
            document.getElementById('success-modal').style.display = 'flex';
            
            document.getElementById('success-modal-btn').onclick = function() {
                document.getElementById('success-modal').style.display = 'none';
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            };
        }

        // --- Delete Logic ---
        function showDeleteModal() {
            document.getElementById('delete-modal').style.display = 'flex';
        }
        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
        }

        function performDelete() {
            closeDeleteModal(); 
            
            fetch("{{ route('hall_bookings.destroy_by_requester', $hallBooking->booking_id) }}", { 
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success || data.message) { 
                    showSuccessModal(data.message || 'Record deleted successfully.', "{{ route('history') }}");
                } else {
                     showSuccessModal('Error deleting record.');
                }
            })
            .catch(err => {
                 console.error(err);
                 alert('Error occurred.');
            });
        }

        // --- Cancel Logic (GA) ---
        function showCancelModal() {
            document.getElementById('cancel-modal').style.display = 'flex';
        }
        function closeCancelModal() {
            document.getElementById('cancel-modal').style.display = 'none';
        }

        function performCancel() {
             const reason = document.getElementById('cancel_reason').value;
             if(!reason.trim()) {
                 alert('Reason is required.');
                 return;
             }

             const formData = new FormData();
             formData.append('_token', '{{ csrf_token() }}');
             formData.append('reason', reason);

             fetch("{{ route('hall_bookings.cancelApproved', $hallBooking->booking_id) }}", {
                method: 'POST',
                body: formData,
                 headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
             })
             .then(response => response.json())
             .then(data => {
                 if(data.success) {
                     closeCancelModal();
                     showSuccessModal('Booking cancelled successfully.');
                 } else {
                     alert('Error: ' + data.message);
                 }
             });
        }

        // --- Re-approve Logic (GA) ---
        function confirmReApprove() {
            if(!confirm('Are you sure you want to re-approve this cancelled booking?')) return;

             fetch("{{ route('hall_bookings.reApprove', $hallBooking->booking_id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
             })
             .then(response => response.json())
             .then(data => {
                 if(data.success) {
                     showSuccessModal('Booking re-approved successfully.');
                 } else {
                     alert('Error: ' + data.message);
                 }
             });
        }

    </script>
@endsection
