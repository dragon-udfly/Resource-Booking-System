@extends('layouts.user_body_layout')

@section('title', 'Preference - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .top-buttons {
            width: 90%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
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
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-back {
            background-color: #6c757d;
        }

        .btn-logout {
            background-color: #dc3545;
        }

        .content-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
        }

        .info-group {
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .info-group span {
            font-weight: bold;
            color: #333;
        }

        .form-container {
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .form-container h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="password"],
        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
        }

        .input-with-button {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-with-button input {
            flex-grow: 1;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
        }
    </style>
@endsection

@section('content')
    @auth
        <section class="banner">
            <div class="top-buttons">
                <a href="#" onclick="history.back(); return false;" class="btn btn-back">Go Back</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-logout logout-trigger">Log Out</button>
                </form>
            </div>

            <div class="content-container">
                <div class="info-group">
                    <h3>Name: <span id="show-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h3>
                </div>

                <div class="form-container">
                    <h3>Profile Details</h3>
                    <form id="profile-update-form" action="{{ route('preference.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required>
                            @error('email')
                                <span style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number"
                                value="{{ Auth::user()->contact_number }}" required>
                            @error('contact_number')
                                <span style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="button" class="btn submit-btn" onclick="confirmProfileUpdate()">Update Profile</button>
                    </form>
                </div>

                <div class="form-container">
                    <h3>Change Password</h3>
                    <form id="password-change-form" action="{{ route('preference.changepassword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="new_passcode">New Password</label>
                            <div class="input-with-button">
                                <input type="password" name="new_passcode" id="new_passcode" required>
                                <button type="button" class="btn btn-secondary" onclick="generatePassword()">Generate</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_passcode_confirmation">Confirm New Password</label>
                            <input type="password" name="new_passcode_confirmation" id="new_passcode_confirmation" required>
                        </div>
                        @error('new_passcode')
                            <span style="color: red; font-size: 0.9em;">{{ $message }}</span>
                        @enderror
                        <button type="button" class="btn submit-btn" onclick="confirmPasswordChange()">Change Password</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Confirmation Modal -->
        <div id="confirmation-modal" class="modal-overlay">
            <div class="modal-content">
                <h3 id="confirmation-title">Confirm Action</h3>
                <p id="confirmation-message"></p>
                <div class="modal-buttons">
                    <button id="confirm-btn" class="btn submit-btn">Yes</button>
                    <button id="cancel-btn" class="btn btn-back" onclick="closeConfirmationModal()">Cancel</button>
                </div>
            </div>
        </div>

    @endauth

    @guest
        <p>You must be logged in to view this page. <a href="{{ route('login') }}">Click here to log in.</a></p>
    @endguest
@endsection

@push('scripts')
    <style>
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
            text-align: center;
            max-width: 450px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .modal-buttons .btn {
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            border: none;
            color: white;
        }
    </style>
    <script>
        // Modal Helper Functions
        function showModal(title, message, buttons) {
            document.getElementById('confirmation-title').textContent = title;
            document.getElementById('confirmation-message').innerHTML = message; // Use innerHTML for lists
            
            const btnContainer = document.querySelector('.modal-buttons');
            btnContainer.innerHTML = ''; // Clear existing buttons

            buttons.forEach(btn => {
                const buttonEl = document.createElement('button');
                buttonEl.textContent = btn.text;
                buttonEl.className = btn.class || 'btn'; // Default class
                if (btn.id) buttonEl.id = btn.id; // Optional ID
                
                buttonEl.addEventListener('click', function(e) {
                    if (btn.onClick) btn.onClick(e);
                });
                
                btnContainer.appendChild(buttonEl);
            });

            const overlay = document.getElementById('confirmation-modal');
            overlay.classList.add('active');
            overlay.style.display = 'flex';
        }

        function closeConfirmationModal() {
            const overlay = document.getElementById('confirmation-modal');
            overlay.classList.remove('active');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }

        // Feature Functions
        function generatePassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById("new_passcode").value = password;
            document.getElementById("new_passcode_confirmation").value = password;
            
            // Toggle visibility to show the generated password
            const input = document.getElementById("new_passcode");
            if (input.type === "password") {
                input.type = "text";
                document.getElementById("new_passcode_confirmation").type = "text";
            }
        }

        function confirmProfileUpdate() {
            showModal("Confirm Profile Update", "Are you sure you want to update your profile details?", [
                { text: 'Yes, Save Changes', class: 'btn submit-btn', onClick: () => submitProfileForm() },
                { text: 'Cancel', class: 'btn btn-back', onClick: closeConfirmationModal }
            ]);
        }

        function submitProfileForm() {
             performSubmit(document.getElementById('profile-update-form'));
        }

        function confirmPasswordChange() {
            const password = document.getElementById('new_passcode').value;
            const confirmPassword = document.getElementById('new_passcode_confirmation').value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return;
            }
            if (password.length < 4) {
                alert("Password must be at least 4 characters.");
                return;
            }

            showModal("Confirm Password Change", "Are you sure you want to change your password?", [
                 { text: 'Yes, Change Password', class: 'btn submit-btn', onClick: () => submitPasswordForm() },
                 { text: 'Cancel', class: 'btn btn-back', onClick: closeConfirmationModal }
            ]);
        }
        
        function submitPasswordForm() {
            performSubmit(document.getElementById('password-change-form'));
        }

        // AJAX Submission Logic
        async function performSubmit(form) {
            // Show processing state
            showModal('Processing...', 'Please wait...', []);

            const formData = new FormData(form);
            const url = form.action;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                // Parse JSON response
                let result;
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    result = await response.json();
                } else {
                    // Fallback if server returns text/html (e.g., error page)
                    const text = await response.text();
                    result = { message: text || response.statusText };
                }

                if (!response.ok) {
                    let message = result.message || 'An error occurred.';
                    if (result.errors) {
                        message = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                        for (const key in result.errors) { 
                            result.errors[key].forEach(err => {
                                message += `<li>${err}</li>`;
                            });
                        }
                        message += '</ul>';
                    }
                    showModal('Error', message, [{ text: 'OK', class: 'btn btn-back', onClick: closeConfirmationModal }]);
                } else {
                    form.reset();
                    showModal('Success', result.message, [{ text: 'OK', class: 'btn submit-btn', onClick: closeConfirmationModal }]);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'btn btn-back', onClick: closeConfirmationModal }]);
            }
        }

        // Close modal on outside click
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('confirmation-modal');
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeConfirmationModal();
                }
            });
        });
    </script>
@endpush