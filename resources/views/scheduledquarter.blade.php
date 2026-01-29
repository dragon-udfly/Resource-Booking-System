@extends('layouts.normal_body_layout')

@section('title', 'Family Quarters Application - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 1.8em; /* Adjusted for long title */
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
            max-width: 1200px; /* Increased max-width */
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

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
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

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .required {
            color: #dc3545;
            margin-left: 5px;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
        </div>
        
        <div class="page-header">
            <h2>Application for Scheduled Quarters</h2>
        </div>

        <div class="form-container">
            <form id="scheduled-quarter-form" action="{{ route('scheduledquarter.store') }}" method="POST">
                @csrf
                
                <h3 class="form-section-title">A) Officer Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="officer_name">1. Name of Officer:<span class="required">*</span></label>
                        <input type="text" id="officer_name" name="officer_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">3. Designation <span class="required">*</span></label>
                        <input type="text" id="designation" name="designation" required>
                    </div>
                    <div class="form-group">
                        <label for="nic">2. National Identity Card Number:<span class="required">*</span></label>
                        <input type="text" id="nic" name="nic" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">4. Gender <span class="required">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_and_grade">5. Service and Grade: <span class="required">*</span></label>
                        <select id="service_and_grade" name="service_and_grade" required>
                            <option value="">Select Service and Grade</option>
                            <option value="1">1 (G I)</option>
                            <option value="2">2 (G II)</option>
                            <option value="3">3 (G III)</option>
                            <option value="4">4 (GIV)</option>
                            <option value="5">5 (G V)</option>
                            <option value="5A">5A</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="permanent_address">6. Permanent Address: <span class="required">*</span></label>
                        <textarea id="permanent_address" name="permanent_address" placeholder="with Grama Niladhari Division:" maxlength="1200" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="temporary_address">7. Temporary Address: </label>
                        <textarea id="temporary_address" name="temporary_address" placeholder="with Grama Niladhari Division:" maxlength="1200"></textarea>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">8. Telephone Number:<span class="required">*</span></label>
                        <input type="tel" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">9. Email Address: </label>
                        <input type="email" id="email" name="email">
                    </div>
                </div> 
                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_salary">10.  Monthly Salary (excluding allowances): <span class="required">*</span></label>
                        <input type="number" id="monthly_salary" name="monthly_salary" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_assumption_of_duties">11.  Date of Assumption of Duties in Vavuniya: <span class="required">*</span></label>
                        <input type="date" id="date_of_assumption_of_duties" name="date_of_assumption_of_duties" required>
                    </div>
                </div>

                <h3 class="form-section-title">B)  Special Reasons for Priority Request</h3>
                <div class="form-row">
                   <div class="form-group">
                        <label for="sq_transfered_officer_priority_request">1. Are you a transferred officer? (Provide descripiton, if available)</label>
                        <textarea id="sq_transfered_officer_priority_request" name="sq_transfered_officer_priority_request" placeholder="Enter description" maxlength="2000" cols="50", rows="7"></textarea>
                    </div>
                </div> 
                <div class="form-row">
                    <div class="form-group">
                        <label for="sq_night_duty_priority_request">2. Are you frequently called for night duty? (Provide descripiton, if available)</label>
                        <textarea id="sq_night_duty_priority_request" name="sq_night_duty_priority_request" placeholder="Enter description" maxlength="2000" cols="50", rows="7"></textarea>
                    </div>
                </div> 
                <div class="form-row">
                    <div class="form-group">
                        <label for="sq_other_special_reason_priority_request">3. Any other special reason? (Provide descripiton, if available)</label>
                        <textarea id="sq_other_special_reason_priority_request" name="sq_other_special_reason_priority_request" placeholder="Enter description" maxlength="2000" cols="50", rows="7"></textarea>
                    </div>
                </div> 
                
                <h3 class="form-section-title">C)  Property Ownership Within 5 km of Vavuniya Town </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sq_property_ownership_details">1. Do you or your spouse own any house or land within a 5 km radius of Vavuniya town? If yes, provide details. </label>
                        <textarea id="sq_property_ownership_details" name="sq_property_ownership_details" placeholder="Enter description" maxlength="2000" cols="50", rows="10"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">D) Requester Details (Enter details to add this application to system)</h3> 
                <div class="form-row">
                    <div class="form-group">
                        <label for="filled_by_nic">Requester Officer's NIC <span class="required">*</span></label>
                        <input type="text" id="filled_by_nic" name="filled_by_nic" required>
                    </div>
                    <div class="form-group">
                        <label for="filled_by_phone">Requester Officer's Phone <span class="required">*</span></label>
                        <input type="tel" id="filled_by_phone" name="filled_by_phone" required>
                    </div>
                </div>
                 <div class="form-group" style="margin-top: 20px; display: flex; align-items: center;">
                    <input type="checkbox" id="confirm_details" name="confirm_details" required style="width: 20px; height: 20px; margin-right: 15px; cursor: pointer;">
                    <label for="confirm_details" style="margin-bottom: 0; cursor: pointer;">I filled this form with applicant details. All details filled here are correct.</label>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn" style="background-color: #007bff;">Submit</button>
                    <button type="reset" class="btn" style="background-color: #6c757d;">Reset</button>
                </div>
            </form>
        </div>
        @include('partials.requester_layout')
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('scheduled-quarter-form'); // Updated form ID
            const requesterOverlay = document.getElementById('requester-overlay');
            const requesterMessage = document.getElementById('requester-message');
            const requesterConfirmBtn = document.getElementById('requester-confirm-btn');
            const requesterCancelBtn = document.getElementById('requester-cancel-btn');

            let isAwaitingConfirmation = false;

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
                isAwaitingConfirmation = false;
            }

            requesterConfirmBtn.addEventListener('click', function() {
                if (isAwaitingConfirmation) {
                    bookingForm.submit(); // This submits the form to the controller
                } else {
                    hideOverlay();
                }
            });

            requesterCancelBtn.addEventListener('click', hideOverlay);
            
            requesterOverlay.addEventListener('click', function(event) {
                if (event.target === requesterOverlay) {
                    hideOverlay();
                }
            });

            // --- Modified Submit Event Listener ---
            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default submission

                const form = event.target;
                const nicInput = form.querySelector('#filled_by_nic');
                const phoneInput = form.querySelector('#filled_by_phone');
                const confirmCheckbox = form.querySelector('#confirm_details');
                const csrfToken = form.querySelector('input[name="_token"]').value;

                // 1. Check if the "Confirm Details" checkbox is checked
                if (!confirmCheckbox.checked) {
                    showOverlay('Please confirm that you have filled the form with the applicant\'s details.', false);
                    return; // Stop the submission process
                }

                const nicNumber = nicInput.value;
                const contactNumber = phoneInput.value;

                // 2. Basic validation for NIC and Phone number fields before fetching
                if (!nicNumber || !contactNumber) {
                    showOverlay('Please enter both Requester NIC and Requester Phone Number.', false);
                    return;
                }

                // 3. Perform verification
                fetch('{{ route('quarters.requester.verify') }}', {
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
                .then(response => {
                    if (!response.ok) {
                        // Handle HTTP errors (e.g., 404, 500)
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Verification successful, show confirmation prompt
                        showOverlay('Requester verified. Are you sure you want to submit this application?', true);
                    } else {
                        // Verification failed, show error message from backend
                        showOverlay(data.message || 'Requester verification failed. Please check the details.', false);
                    }
                })
                .catch(error => {
                    console.error('Verification Fetch Error:', error);
                    showOverlay('An error occurred during verification. Please try again. (Details: ' + error.message + ')', false);
                });
            });
        });
    </script>
@endpush