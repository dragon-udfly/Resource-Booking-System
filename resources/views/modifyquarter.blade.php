@extends('layouts.admin_body_layout')

@section('title', 'Modify Quarter - District Secretariat Vavuniya')

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
            min-width: 280px;
            /* Ensure fields don't get too small */
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="tel"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group.full-width {
            flex: 1 1 100%;
            /* Take full width */
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

        .submit-btn,
        .reset-btn {
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
            text-decoration: none;
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
            <a href="{{ route('quarters.index') }}" class="submit-btn"
                style="background-color: #6c757d; text-decoration: none;">Back</a>
        </div>
        <div class="page-header">
            <h2>Modify Quarter Details</h2>
            <p>Fill in the details below to modify existing quarter details in the system</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Fields marked with <span class="required">*</span> are required. Please ensure all information is
                    accurate before submitting.</p>
            </div>

            <form id="modify-quarter-form" action="{{ route('quarters.update', $quarter) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group">
                        <label for="old_quarter_no">Old Quarter No</label>
                        <input type="text" id="old_quarter_no" name="old_quarter_no"
                            value="{{ old('old_quarter_no', $quarter->old_quarter_no) }}">
                    </div>
                    <div class="form-group">
                        <label for="new_quarter_no">New Quarter No</label>
                        <input type="text" id="new_quarter_no" name="new_quarter_no"
                            value="{{ old('new_quarter_no', $quarter->new_quarter_no) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quarter_type">Quarter Type <span class="required">*</span></label>
                        <select id="quarter_type" name="quarter_type" required>
                            <option value="">Select Quarter Type</option>
                            <option value="Family" {{ old('quarter_type', $quarter->quarter_type) == 'Family' ? 'selected' : '' }}>Family</option>
                            <option value="Scheduled" {{ old('quarter_type', $quarter->quarter_type) == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_grade">Service Grade</label>
                        <select id="service_grade" name="service_grade">
                            <option value="">Select Grade</option>
                            <option value="1" {{ old('service_grade', $quarter->service_grade) == '1' ? 'selected' : '' }}>1
                            </option>
                            <option value="2" {{ old('service_grade', $quarter->service_grade) == '2' ? 'selected' : '' }}>2
                            </option>
                            <option value="3" {{ old('service_grade', $quarter->service_grade) == '3' ? 'selected' : '' }}>3
                            </option>
                            <option value="4" {{ old('service_grade', $quarter->service_grade) == '4' ? 'selected' : '' }}>4
                            </option>
                            <option value="5" {{ old('service_grade', $quarter->service_grade) == '5' ? 'selected' : '' }}>5
                            </option>
                            <option value="5A" {{ old('service_grade', $quarter->service_grade) == '5A' ? 'selected' : '' }}>
                                5A</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="location">Location <span class="required">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $quarter->location) }}"
                            required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Quarter Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="">Select status</option>
                            <option value="Unallocated" {{ old('status', $quarter->status) == 'Unallocated' ? 'selected' : '' }}>Unallocated</option>
                            <option value="Allocated" {{ old('status', $quarter->status) == 'Allocated' ? 'selected' : '' }}>
                                Allocated</option>
                            <option value="Repair" {{ old('status', $quarter->status) == 'Repair' ? 'selected' : '' }}>Repair
                            </option>
                            <option value="Demolished" {{ old('status', $quarter->status) == 'Demolished' ? 'selected' : '' }}>Demolished</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="occupant_number">Number of Allowed Occupants</label>
                        <input type="number" id="occupant_number" name="occupant_number"
                            value="{{ old('occupant_number', $quarter->occupant_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="current_occupant_number">Current Occupant Number</label>
                        <input type="number" id="current_occupant_number" name="current_occupant_number"
                            value="{{ old('current_occupant_number', $quarter->current_occupant_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="allowed_gender">Allowed Occupant Gender</label>
                        <select id="allowed_gender" name="allowed_gender">
                            <option value="">Not Specified</option>
                            <option value="Female" {{ old('allowed_gender', $quarter->allowed_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Male" {{ old('allowed_gender', $quarter->allowed_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="special_notice">Special Notice</label>
                        <textarea id="special_notice" name="special_notice"
                            rows="3">{{ old('special_notice', $quarter->special_notice) }}</textarea>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="submit-btn">Update Quarter</button>
                    <button type="reset" class="reset-btn">Reset</button>
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
            const form = document.getElementById('modify-quarter-form');
            if (form) {
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

                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const confirmButtons = [
                        { text: 'Yes, Save Changes', class: 'submit-btn', onClick: () => performSubmit() },
                        { text: 'Cancel', class: 'reset-btn', onClick: hideModal }
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
                            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
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
                            showModal('Error', errorMessage, [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
                        } else {
                            showModal('Success', result.message, [
                                { text: 'Continue Editing', class: 'submit-btn', onClick: hideModal },
                                { text: 'View All Quarters', class: 'btn', style: 'background-color: #6c757d;', onClick: () => window.location.href = "{{ route('quarters.index') }}" }
                            ]);
                        }
                    } catch (error) {
                        showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'reset-btn', onClick: hideModal }]);
                    }
                };

                if (modalOverlay) {
                    modalOverlay.addEventListener('click', function (event) {
                        if (event.target === modalOverlay) {
                            hideModal();
                        }
                    });
                }
            }
        });
    </script>
@endpush