@extends('layouts.admin_body_layout')

@section('title', 'Add Hall - District Secretariat Vavuniya')

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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical; /* Allow vertical resizing */
        }

        .form-group.full-width {
            flex: 1 1 100%; /* Take full width */
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Add New Hall</h2>
            <p>Fill in the details below to add a new hall to the system</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Fields marked with <span style="color: #ff0000;">*</span> are required. Please ensure all information is accurate before submitting.</p>
            </div>

            <form id="addHallForm" action="{{ route('halls.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="hall_type">Hall Type <span class="required">*</span></label>
                        <input type="text" id="hall_type" name="hall_type" placeholder="Enter hall type" value="{{ old('hall_type') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="capacity">Capacity (People) <span class="required">*</span></label>
                        <input type="number" id="capacity" name="capacity" placeholder="Enter seating capacity" value="{{ old('capacity') }}" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" placeholder="Enter detailed description of the hall" required maxlength="1200">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="hall_status">Hall Status <span class="required">*</span></label>
                        <select id="hall_status" name="hall_status" required>
                            <option value="available" @if(old('hall_status') == 'available') selected @endif>Available</option>
                            <option value="unavailable" @if(old('hall_status') == 'unavailable') selected @endif>Unavailable</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="special_notice">Special Notice</label>
                        <textarea id="special_notice" name="special_notice" placeholder="Enter notice if the hall is temporary unavailable, including reasons and time period." maxlength="1200">{{ old('special_notice') }}</textarea>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="submit-btn">Add Hall</button>
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
    const form = document.getElementById('addHallForm');
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
                { text: 'Yes, Add Hall', class: 'submit-btn', onClick: () => performSubmit() },
                { text: 'Cancel', class: 'reset-btn', onClick: hideModal }
            ];
            showModal('Confirm Submission', 'Are you sure you want to add this hall?', confirmButtons);
        });

        const performSubmit = async () => {
            showModal('Processing...', 'Submitting hall details, please wait...', []);

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
                    const errorButtons = [{ text: 'OK', class: 'reset-btn', onClick: hideModal }];
                    showModal('Error', result.message || 'An unknown error occurred.', errorButtons);
                } else {
                    const successButtons = [
                        { text: 'Add Another Hall', class: 'submit-btn', onClick: () => { form.reset(); hideModal(); } },
                        { text: 'View All Halls', class: 'back-button', onClick: () => window.location.href = "{{ route('halls.index') }}" }
                    ];
                    showModal('Success', result.message, successButtons);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const errorButtons = [{ text: 'OK', class: 'reset-btn', onClick: hideModal }];
                showModal('Request Failed', 'Could not connect to the server.', errorButtons);
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