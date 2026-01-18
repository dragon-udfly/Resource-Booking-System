<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hall - District Secretariat Vavuniya</title>
    <link href="{{ asset('icons/right_logo.png') }}" rel='icon' type='image/png'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 10px 20px;
            background-color: #e9ecef;
            border-top: 1px solid #dee2e6;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            margin-right: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            min-height: 65vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 1.1em;
            color: #555;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
        }

        .required {
            color: #dc3545;
            margin-left: 5px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .submit-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="submit-btn" style="text-decoration: none; background-color: #6c757d;">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Hall Booking Form</h2>
            <p>Fill in the details below to book a hall</p>
        </div>

        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="booking-form" action="{{ route('hall_bookings.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name">Applicant Name <span class="required">*</span></label>
                        <input type="text" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="applicant_email">Applicant Email <span class="required">*</span></label>
                        <input type="email" id="applicant_email" name="applicant_email" value="{{ old('applicant_email') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_type">Applicant Type <span class="required">*</span></label>
                        <select id="applicant_type" name="applicant_type" required>
                            <option value="Internal" @if(old('applicant_type') == 'Internal') selected @endif>Internal</option>
                            <option value="External" @if(old('applicant_type') == 'External') selected @endif>External</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hall_id">Hall Type <span class="required">*</span></label>
                        <select id="hall_id" name="hall_id" required>
                            <option value="">-- Select a Hall --</option>
                            @foreach($halls as $hall)
                                <option value="{{ $hall->hall_id }}" @if(old('hall_id') == $hall->hall_id) selected @endif>{{ $hall->hall_type }} (Capacity: {{ $hall->capacity }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="programme">Programme/Event <span class="required">*</span></label>
                        <input type="text" id="programme" name="programme" value="{{ old('programme') }}" required>
                    </div>
                </div>

                <div class="form-row">
                     <div class="form-group">
                        <label for="event_date">Event Date <span class="required">*</span></label>
                        <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="event_time">Event Time <span class="required">*</span></label>
                        <input type="time" id="event_time" name="event_time" value="{{ old('event_time') }}" required>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group">
                        <label for="participants">Number of Participants <span class="required">*</span></label>
                        <input type="number" id="participants" name="participants" value="{{ old('participants') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="event_duration">Event Duration (hours) <span class="required">*</span></label>
                        <input type="number" id="event_duration" name="event_duration" step="0.1" value="{{ old('event_duration') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="paid_status">Paid Status <span class="required">*</span></label>
                        <select id="paid_status" name="paid_status" required>
                            <option value="Not Required" @if(old('paid_status') == 'Not Required') selected @endif>Not Required</option>
                            <option value="Yes" @if(old('paid_status') == 'Yes') selected @endif>Yes</option>
                            <option value="Pending" @if(old('paid_status') == 'Pending') selected @endif>Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_emergency_booking">Emergency Booking <span class="required">*</span></label>
                        <select id="is_emergency_booking" name="is_emergency_booking" required>
                            <option value="0" @if(old('is_emergency_booking') == '0') selected @endif>No</option>
                            <option value="1" @if(old('is_emergency_booking') == '1') selected @endif>Yes</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="filled_by_nic">Requester Officer's NIC <span class="required">*</span></label>
                        <input type="text" id="filled_by_nic" name="filled_by_nic" value="{{ old('filled_by_nic') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="filled_by_phone">Requester Officer's Phone <span class="required">*</span></label>
                        <input type="tel" id="filled_by_phone" name="filled_by_phone" value="{{ old('filled_by_phone') }}" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px; display: flex; align-items: center;">
                    <input type="checkbox" id="confirm_details" name="confirm_details" required style="width: 20px; height: 20px; margin-right: 15px; cursor: pointer;">
                    <label for="confirm_details" style="margin-bottom: 0; cursor: pointer;">I filled this form with applicant details. All details filled here are true.</label>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Submit for Approval</button>
                </div>
            </form>
        </div>
    </section>

    @include('partials.footer')
    @include('partials.requester_layout')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('booking-form');
            const requesterOverlay = document.getElementById('requester-overlay');
            const requesterMessage = document.getElementById('requester-message');
            const requesterConfirmBtn = document.getElementById('requester-confirm-btn');
            const requesterCancelBtn = document.getElementById('requester-cancel-btn');

            const eventDateInput = document.getElementById('event_date');
            const eventTimeInput = document.getElementById('event_time');

            // Function to update the minimum time allowed based on the selected date
            function updateMinTime() {
                const today = new Date().toISOString().split('T')[0];
                if (eventDateInput.value === today) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    eventTimeInput.min = `${hours}:${minutes}`;
                } else {
                    eventTimeInput.removeAttribute('min');
                }
            }

            // Initial check and event listener for date changes
            updateMinTime();
            eventDateInput.addEventListener('change', updateMinTime);

            let isAwaitingConfirmation = false; // Flag to track if overlay is for confirmation

            function showOverlay(message, isConfirmation = false) {
                requesterMessage.textContent = message;
                requesterOverlay.style.display = 'flex';
                isAwaitingConfirmation = isConfirmation;

                if (isConfirmation) {
                    requesterConfirmBtn.textContent = 'Submit';
                    requesterConfirmBtn.style.display = 'inline-block';
                    requesterCancelBtn.style.display = 'inline-block';
                } else {
                    requesterConfirmBtn.textContent = 'OK';
                    requesterConfirmBtn.style.display = 'inline-block';
                    requesterCancelBtn.style.display = 'none';
                }
            }

            function hideOverlay() {
                requesterOverlay.style.display = 'none';
                requesterMessage.textContent = '';
                isAwaitingConfirmation = false; // Reset flag
            }

            // Event listener for the Confirm/OK button
            requesterConfirmBtn.addEventListener('click', function() {
                if (isAwaitingConfirmation) {
                    bookingForm.submit(); // Submit the form if it was a confirmation
                } else {
                    hideOverlay(); // Just close for simple messages
                }
            });

            // Event listener for the Cancel button
            requesterCancelBtn.addEventListener('click', hideOverlay);
            
            // Optionally, close overlay when clicking outside the message box (but not on the content itself)
            requesterOverlay.addEventListener('click', function(event) {
                if (event.target === requesterOverlay) {
                    hideOverlay();
                }
            });

            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const form = event.target;
                const nicNumber = form.querySelector('#filled_by_nic').value;
                const contactNumber = form.querySelector('#filled_by_phone').value;
                const csrfToken = form.querySelector('input[name="_token"]').value;

                // Perform AJAX verification
                fetch('{{ route('requester.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        nic_number: nicNumber,
                        contact_number: contactNumber
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // If verification is successful, show confirmation overlay
                        showOverlay('Requester verified. Are you sure you want to submit this booking request?', true);
                    } else {
                        // Show error message in the overlay
                        showOverlay(data.message, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showOverlay('An error occurred during verification. Please try again.', false);
                });
            });
        });
    </script>
</body>
</html>
