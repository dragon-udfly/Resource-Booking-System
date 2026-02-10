@extends('layouts.admin_body_layout')

@section('title', 'Grade Salary Settings - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            color: white;
            background-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 800px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn"
                style="background-color: #6c757d; text-decoration: none">Back</a>
        </div>
        <div class="page-header">
            <h2>Grade Salary Settings</h2>
            <p>Change Salary Range for Service Grade</p>
        </div>

        <div class="form-container">
            <form id="grade-salary-form" action="{{ route('gradesalary.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <table>
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Minimum Salary</th>
                            <th>Maximum Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            @php
                                $gradeKey = str_replace([' ', '(', ')', '-'], '_', $grade);
                                $currentMin = $gradeSalarySettings[$grade]->min_salary ?? '';
                                $currentMax = $gradeSalarySettings[$grade]->max_salary ?? '';
                            @endphp
                            <tr>
                                <td>{{ $grade }}</td>
                                <td><input type="number" name="grade_{{ $gradeKey }}_min"
                                        value="{{ old('grade_' . $gradeKey . '_min', $currentMin) }}" required></td>
                                <td><input type="number" name="grade_{{ $gradeKey }}_max"
                                        value="{{ old('grade_' . $gradeKey . '_max', $currentMax) }}" required></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="button-group">
                    <button type="submit" class="btn">Save Changes</button>
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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('grade-salary-form');
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
                        buttonEl.className = 'btn';
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
                        { text: 'Yes, Save', onClick: () => performSubmit() },
                        { text: 'Cancel', onClick: hideModal }
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
                            showModal('Error', message, [{ text: 'OK', onClick: hideModal }]);
                        } else {
                            showModal('Success', JSON.parse(responseText).message, [{ text: 'OK', onClick: hideModal }]);
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', onClick: hideModal }]);
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