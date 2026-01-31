@extends('layouts.admin_body_layout')

@section('title', 'Modify Account - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        /* Reusing styles for consistency */
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
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
        }
        .form-group input[readonly] {
            background-color: #e9ecef;
        }
        .required {
            color: #dc3545;
            margin-left: 5px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            flex: 0 0 auto;
            min-width: unset;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        .checkbox-group label {
            margin-bottom: 0;
            font-weight: normal;
        }
        .input-with-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-with-toggle input {
            flex-grow: 1;
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
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn submit-btn" style="background-color: #6c757d;">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Modify Officer Account ({{ $user->user_id }})</h2>
            <p>Update the details for the selected officer</p>
        </div>

        <div class="form-container">


            <form id="modify-user-form" action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Designation(Read Only)</label>
                        <input type="text" id="designation" name="designation" value="{{ old('designation', $user->designation) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_number">Phone Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="nic_number">NIC Number (Read Only)</label>
                        <input type="text" id="nic_number" name="nic_number" value="{{ $user->nic_number }}" readonly>
                    </div>
                </div>

                <hr style="margin: 30px 0;">

                <h4 style="margin-bottom: 15px;">Change Passcode</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="passcode">New Passcode</label>
                        <div class="input-with-toggle">
                            <input type="password" id="passcode" name="passcode">
                            <button type="button" class="btn submit-btn" style="background-color: #6c757d;" data-target="passcode">Show</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="passcode_confirmation">Confirm New Passcode</label>
                        <div class="input-with-toggle">
                            <input type="password" id="passcode_confirmation" name="passcode_confirmation">
                            <button type="button" class="btn submit-btn" style="background-color: #6c757d;" data-target="passcode_confirmation">Show</button>
                        </div>
                    </div>
                </div>

                <hr style="margin: 30px 0;">
                
                <h4 style="margin-bottom: 15px;">Permissions</h4>
                <div id="permissions" class="form-row">
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_officers" name="permissions[]" value="view_officers" @if($user->permissions && $user->permissions->view_officers) checked @endif>
                        <label for="view_officers">View Officers</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_halls" name="permissions[]" value="view_halls" @if($user->permissions && $user->permissions->view_halls) checked @endif>
                        <label for="view_halls">View Halls</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_quarters" name="permissions[]" value="view_quarters" @if($user->permissions && $user->permissions->view_quarters) checked @endif>
                        <label for="view_quarters">View Quarters</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="view_audit_log" name="permissions[]" value="view_audit_log" @if($user->permissions && $user->permissions->view_audit_log) checked @endif>
                        <label for="view_audit_log">View Audit Log</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="administrative_officer_approval" name="permissions[]" value="administrative_officer_approval" @if($user->permissions && $user->permissions->administrative_officer_approval) checked @endif>
                        <label for="administrative_officer_approval">Administrative Officer Approval</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="additional_government_agent_approval" name="permissions[]" value="additional_government_agent_approval" @if($user->permissions && $user->permissions->additional_government_agent_approval) checked @endif>
                        <label for="additional_government_agent_approval">Additional Government Agent Approval</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="government_agent_approval" name="permissions[]" value="government_agent_approval" @if($user->permissions && $user->permissions->government_agent_approval) checked @endif>
                        <label for="government_agent_approval">Government Agent Approval</label>
                    </div>
                     <div class="form-group checkbox-group">
                        <input type="checkbox" id="account_setting" name="permissions[]" value="account_setting" @if($user->permissions && $user->permissions->account_setting) checked @endif>
                        <label for="account_setting">Preference</label>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="requester" name="permissions[]" value="requester" @if($user->permissions && $user->permissions->requester) checked @endif>
                        <label for="requester">Requester</label>
                    </div>
                </div>

                <div class="button-group">

                    <button type="submit" class="submit-btn">Save Changes</button>
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
    
    <style>
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content { background: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 450px; width: 90%; transform: scale(0.9); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-buttons { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .modal-buttons .btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
    <script>
        // --- START: Original Show/Hide Passcode Script ---
        const toggleButtons = document.querySelectorAll('.input-with-toggle .btn');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const targetInput = document.getElementById(targetId);
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    this.textContent = 'Hide';
                } else {
                    targetInput.type = 'password';
                    this.textContent = 'Show';
                }
            });
        });
        // --- END: Original Show/Hide Passcode Script ---

        // --- START: New Modal and AJAX Script ---
        const form = document.getElementById('modify-user-form');
        if (form) {
            const modalOverlay = document.getElementById('modal-overlay');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const modalButtons = document.getElementById('modal-buttons');

            const showModal = (title, message, buttons) => {
                if (!modalOverlay) return; // Failsafe
                modalTitle.textContent = title;
                modalMessage.innerHTML = message;
                modalButtons.innerHTML = '';
                buttons.forEach(btn => {
                    const buttonEl = document.createElement('button');
                    buttonEl.textContent = btn.text;
                    buttonEl.className = `btn ${btn.class}`;
                    if (btn.style) {
                        buttonEl.style.cssText += btn.style;
                    }
                    buttonEl.addEventListener('click', btn.onClick);
                    modalButtons.appendChild(buttonEl);
                });
                modalOverlay.classList.add('active');
            };

            const hideModal = () => {
                if (!modalOverlay) return; // Failsafe
                modalOverlay.classList.remove('active');
            };

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const confirmButtons = [
                    { text: 'Yes, Save Changes', class: 'submit-btn', onClick: () => performSubmit() },
                    { text: 'Cancel', class: 'reset-btn', style:'background-color: #6c757d; color: white;', onClick: hideModal }
                ];
                showModal('Confirm Changes', 'Are you sure you want to save these changes?', confirmButtons);
            });

            const performSubmit = async () => {
                showModal('Processing...', 'Saving changes, please wait...', []);
                const formData = new FormData(form);
                const url = form.action;
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
                        body: formData
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        let errorMessage = result.message || 'An unknown validation error occurred.';
                        if (result.errors) {
                            errorMessage = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                            for (const key in result.errors) { errorMessage += `<li>${result.errors[key][0]}</li>`; }
                            errorMessage += '</ul>';
                        }
                        showModal('Error', errorMessage, [{ text: 'OK', class: 'reset-btn', style:'background-color: #6c757d; color: white;', onClick: hideModal }]);
                    } else {
                        showModal('Success', result.message, [
                            { text: 'Continue Editing', class: 'submit-btn', onClick: hideModal },
                            { text: 'View All Officers', class: 'back-button', style:'background-color: #6c757d; color: white;', onClick: () => window.location.href = "{{ route('officers.index') }}" }
                        ]);
                    }
                } catch (error) {
                    showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'reset-btn', style:'background-color: #6c757d; color: white;', onClick: hideModal }]);
                }
            };

            if(modalOverlay) {
                modalOverlay.addEventListener('click', function(event) {
                    if (event.target === modalOverlay) {
                        hideModal();
                    }
                });
            }
        }
</script>
@endsection
