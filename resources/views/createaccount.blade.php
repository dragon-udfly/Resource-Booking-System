@extends('layouts.admin_body_layout')

@section('title', 'Create Account - District Secretariat Vavuniya')

@section('page_styles')
    <style>
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

        .form-info {
            background-color: #e9f7ef;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #218838;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            min-width: 280px; /* Ensure fields don't get too small */
        }

        .form-group label #p_p {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            flex: 0 0 auto; /* Prevent checkboxes from stretching */
            min-width: unset; /* Override min-width from form-group */
        }

        .checkbox-group input[type="checkbox"] {
            width: auto; /* Reset width */
            margin-right: 10px;
            transform: scale(1.2); /* Make checkbox slightly larger */
        }

        .checkbox-group label {
            margin-bottom: 0; /* Remove bottom margin */
            font-weight: normal; /* Reset font-weight */
            color: #555;
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

        .submit-btn, .reset-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
        }

        .submit-btn:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .reset-btn {
            background-color: #6c757d;
            color: white;
        }

        .reset-btn:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
    <section class="banner">
         <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="submit-btn" style="background-color: #6c757d; text-decoration: none">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Create New Account</h2>
            <p>Fill in the details below to create a new user account</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Fields marked with <span style="color: #ff0000;">*</span> are required. Please ensure all information is accurate before submitting.</p>
            </div>



            <form id="create-account-form" action="{{ route('createaccount.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" placeholder="Enter last name" required>
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation(Type designation correctly) <span class="required">*</span></label>
                        <input type="text" id="designation" name="designation" placeholder="Enter designation" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required value="{{ old('email') }}">
                        @error('email')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_number">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="contact_number" name="contact_number" placeholder="Enter phone number" required value="{{ old('contact_number') }}">
                        @error('contact_number')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nic_number">NIC Number <span class="required">*</span></label>
                        <input type="text" id="nic_number" name="nic_number" placeholder="Enter NIC number" required value="{{ old('nic_number') }}">
                        @error('nic_number')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="passcode">Passcode <span class="required">*</span></label>
                        <input type="password" id="passcode" name="passcode" placeholder="Enter passcode" required>
                    </div>
                </div>
                <p id="p_p" style="color:#ff0000">Permissions</p><br />
                <div id="permissions" class="form-row">
                    <div id="permission1" class="form-group checkbox-group">
                        <input type="checkbox" id="view_officers" name="permissions[]" value="view_officers">
                        <label for="view_officers">View Officers</label>
                    </div>
                    <div id="permission2" class="form-group checkbox-group">
                        <input type="checkbox" id="view_halls" name="permissions[]" value="view_halls">
                        <label for="view_halls">View Halls</label>
                    </div>
                    <div id="permission3" class="form-group checkbox-group">
                        <input type="checkbox" id="view_quarters" name="permissions[]" value="view_quarters">
                        <label for="view_quarters">View Quarters</label>
                    </div>
                    <div id="permission4" class="form-group checkbox-group">
                        <input type="checkbox" id="view_audit_log" name="permissions[]" value="view_audit_log">
                        <label for="view_audit_log">View Audit Log</label>
                    </div>
                    <div id="permission5" class="form-group checkbox-group">
                        <input type="checkbox" id="administrative_officer_approval" name="permissions[]" value="administrative_officer_approval">
                        <label for="administrative_officer_approval">Administrative Officer Approval</label>
                    </div>
                    <div id="permission6" class="form-group checkbox-group">
                        <input type="checkbox" id="additional_government_agent_approval" name="permissions[]" value="additional_government_agent_approval">
                        <label for="additional_government_agent_approval">Additional Government Agent Approval</label>
                    </div>
                    <div id="permission7" class="form-group checkbox-group">
                        <input type="checkbox" id="government_agent_approval" name="permissions[]" value="government_agent_approval">
                        <label for="government_agent_approval">Government Agent Approval</label>
                    </div>
                    <div id="permission8" class="form-group checkbox-group">
                        <input type="checkbox" id="account_setting" name="permissions[]" value="account_setting">
                        <label for="account_setting">Preference</label>
                    </div>
                    <div id="permission9" class="form-group checkbox-group">
                        <input type="checkbox" id="requester" name="permissions[]" value="requester">
                        <label for="requester">Requester</label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Create Account</button>
                    <button type="reset" class="reset-btn">Reset Form</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Generic Modal Overlay -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <div id="modal-buttons" class="modal-buttons"></div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 450px; width: 90%; transform: scale(0.9); transition: transform 0.3s ease; }
    .modal-overlay.active .modal-content { transform: scale(1); }
    .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
    .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; border: none; color: white; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-account-form');
    if (form) {
        const modalOverlay = document.getElementById('modal-overlay');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const modalButtons = document.getElementById('modal-buttons');

        const showModal = (title, message, buttons) => {
            modalTitle.textContent = title;
            modalMessage.innerHTML = message; // Use innerHTML to render error lists
            modalButtons.innerHTML = '';
            buttons.forEach(btn => {
                const buttonEl = document.createElement('button');
                buttonEl.textContent = btn.text;
                buttonEl.className = `btn ${btn.class}`;
                buttonEl.addEventListener('click', btn.onClick);
                modalButtons.appendChild(buttonEl);
            });
            modalOverlay.classList.add('active');
        };

        const hideModal = () => {
            modalOverlay.classList.remove('active');
        };

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const confirmButtons = [
                { text: 'Yes, Create Account', class: 'submit-btn', onClick: () => performSubmit() },
                { text: 'Cancel', class: 'reset-btn', onClick: hideModal }
            ];
            showModal('Confirm Action', 'Are you sure you want to create this account?', confirmButtons);
        });

        const performSubmit = async () => {
            showModal('Processing...', 'Creating account, please wait...', []);

            const formData = new FormData(form);
            const url = form.action;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    let errorMessage = result.message || 'An unknown validation error occurred.';
                    if (result.errors) {
                        errorMessage = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                        for (const key in result.errors) {
                            errorMessage += `<li>${result.errors[key][0]}</li>`;
                        }
                        errorMessage += '</ul>';
                    }
                    const errorButtons = [{ text: 'OK', class: 'reset-btn', onClick: hideModal }];
                    showModal('Error', errorMessage, errorButtons);
                } else {
                    const successButtons = [
                        { text: 'Create Another', class: 'submit-btn', onClick: () => { form.reset(); hideModal(); } },
                        { text: 'View All Officers', class: 'back-button', onClick: () => window.location.href = "{{ route('officers.index') }}" }
                    ];
                    showModal('Success', result.message, successButtons);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'reset-btn', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server. Please check your network.', errorButtons);
            }
        };
        
        modalOverlay.addEventListener('click', function(event) {
            if (event.target === modalOverlay) {
                hideModal();
            }
        });
    }
});
</script>
@endpush
