@extends('layouts.admin_body_layout')

@section('title', 'Admin Preference - District Secretariat Vavuniya')

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
                    <h3>Update Profile Details</h3>
                    <form id="update-profile-form" action="{{ route('adminpreference.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ Auth::user()->first_name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ Auth::user()->last_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nic_number">NIC Number</label>
                            <input type="text" id="nic_number" name="nic_number" value="{{ Auth::user()->nic_number }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number"
                                value="{{ Auth::user()->contact_number }}" required>
                        </div>
                        <button type="submit" class="btn submit-btn">Update Profile</button>
                    </form>
                </div>

                <div class="form-container">
                    <h3>Change Password</h3>
                    <form id="change-password-form" action="{{ route('preference.changepassword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="new_passcode">New Password</label>
                            <div class="input-with-button">
                                <input type="password" id="new_passcode" name="new_passcode" required>
                                <button type="button" class="btn btn-back toggle-passcode-visibility"
                                    data-target="new_passcode">Show</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="new_passcode_confirmation">Confirm New Password</label>
                            <div class="input-with-button">
                                <input type="password" id="new_passcode_confirmation" name="new_passcode_confirmation" required>
                                <button type="button" class="btn btn-back toggle-passcode-visibility"
                                    data-target="new_passcode_confirmation">Show</button>
                            </div>
                        </div>
                        <button type="submit" class="btn submit-btn">Save Password</button>
                    </form>
                </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            // --- Show/Hide Passcode Script ---
            const toggleButtons = document.querySelectorAll('.toggle-passcode-visibility');
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

            // --- Modal and AJAX Script ---
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
                    buttonEl.className = `btn ${btn.class}`;
                    if (btn.style) buttonEl.style.cssText += btn.style;
                    buttonEl.addEventListener('click', btn.onClick);
                    modalButtons.appendChild(buttonEl);
                });
                modalOverlay.classList.add('active');
            };

            const hideModal = () => {
                modalOverlay.classList.remove('active');
            };

            if (modalOverlay) {
                modalOverlay.addEventListener('click', function (event) {
                    if (event.target === modalOverlay) {
                        hideModal();
                    }
                });
            }

            // Helper function for AJAX submission
            const handleFormSubmit = (form, confirmTitle, confirmMessage) => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const confirmButtons = [
                        { text: 'Yes, Save', class: 'submit-btn', onClick: () => performSubmit(form) },
                        { text: 'Cancel', class: 'btn-back', onClick: hideModal }
                    ];
                    showModal(confirmTitle, confirmMessage, confirmButtons);
                });
            };

            const performSubmit = async (form) => {
                showModal('Processing...', 'Saving changes...', []);
                const formData = new FormData(form);
                const url = form.action;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const responseText = await response.text();

                    if (!response.ok) {
                        let message = `Error: ${response.status} ${response.statusText}`;
                        try {
                            const result = JSON.parse(responseText);
                            if (result.errors) {
                                message = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                                for (const key in result.errors) { message += `<li>${result.errors[key][0]}</li>`; }
                                message += '</ul>';
                            } else {
                                message = result.message || message;
                            }
                        } catch (e) { /* Ignore */ }
                        showModal('Error', message, [{ text: 'OK', class: 'btn-back', onClick: hideModal }]);
                    } else {
                        // Refresh page if profile updated to show new name in banner etc, or just reset/show success
                        try {
                            const result = JSON.parse(responseText);
                            showModal('Success', result.message, [{
                                text: 'OK', class: 'submit-btn', onClick: () => {
                                    hideModal();
                                    if (form.id === 'update-profile-form') window.location.reload(); // Reload to update header name
                                    if (form.id === 'change-password-form') form.reset();
                                }
                            }]);
                        } catch (e) {
                            showModal('Success', 'Changes saved successfully.', [{
                                text: 'OK', class: 'submit-btn', onClick: () => {
                                    hideModal();
                                    window.location.reload();
                                }
                            }]);
                        }
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'btn-back', onClick: hideModal }]);
                }
            };

            const changePasswordForm = document.getElementById('change-password-form');
            if (changePasswordForm) {
                handleFormSubmit(changePasswordForm, 'Confirm Passcode Change', 'Are you sure you want to change your passcode?');
            }

            const updateProfileForm = document.getElementById('update-profile-form');
            if (updateProfileForm) {
                handleFormSubmit(updateProfileForm, 'Confirm Profile Update', 'Are you sure you want to update your profile details?');
            }
        });
    </script>
@endpush