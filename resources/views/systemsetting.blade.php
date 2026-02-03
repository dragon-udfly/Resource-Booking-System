@extends('layouts.admin_body_layout')

@section('title', 'System Settings - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .settings-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin-top: 20px;
        }

        .settings-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .settings-header h2 {
            font-size: 2em;
            font-weight: bold;
            color: rgb(6, 4, 60);
        }

        .settings-group {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .settings-group:last-child {
            border-bottom: none;
        }

        .settings-group h3 {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #555;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
        }

        .btn-save {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #0056b3;
        }

        /* Advanced Settings Table Styles */
        .advanced-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .advanced-table th,
        .advanced-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .advanced-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Overlay Styles */
        #confirmation-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .overlay-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="settings-container">
            <div class="settings-header">
                <h2>System Settings</h2>
                <p>Configure general system parameters and preferences.</p>
            </div>

            @if(session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="settings-group">
                <h3>Email Configuration Test</h3>
                <p style="margin-bottom: 20px; color: #666;">Use this form to test if your email settings (.env
                    configuration) are working correctly.</p>

                <form action="{{ route('settings.email.test') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="test_email">Recipient Email</label>
                            <input type="email" id="test_email" name="test_email" required
                                placeholder="Enter recipient email">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required placeholder="Test Email Subject">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email-body">Message Body</label>
                            <textarea id="email-body" name="email-body" rows="4" required
                                style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; resize: vertical;">This is a test email from the Resource Booking System.</textarea>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <button type="submit" class="btn-save">Send Test Email</button>
                    </div>
                </form>
            </div>
            <br><br><br>

            <!-- Backup Section -->
            <br><br>
            <h3 style="text-align: center; color: #28a745;">Backup & Restore</h3>

            <table class="advanced-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Database Backup: Save a SQL dump of the current database state to prevent data loss.
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                <!-- Backup Form -->
                                <form action="{{ route('settings.backup.db') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-save"
                                        style="background-color: #28a745;">Backup</button>
                                </form>

                                <!-- Restore Form -->
                                <form action="{{ route('settings.restore.db') }}" method="POST"
                                    enctype="multipart/form-data"
                                    onsubmit="return confirm('WARNING: This will replace all current data with the backup file. This action cannot be undone. Are you sure?');">
                                    @csrf
                                    <label for="backup_file" class="btn-save"
                                        style="background-color: #ffc107; color: #000; cursor: pointer; margin: 0; font-weight: bold; padding: 12px 25px;">
                                        Restore
                                    </label>
                                    <input type="file" name="backup_file" id="backup_file" accept=".sql"
                                        style="display: none;" onchange="this.form.submit()">
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Hall Details Record: Save a SQL dump of all hall records.
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                <form action="{{ route('settings.backup.halls') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                                </form>
                                <form action="{{ route('settings.restore.halls') }}" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return confirm('WARNING: This will replace all Hall data and cannot be undone. Are you sure?');">
                                    @csrf
                                    <label for="restore_halls" class="btn-save" style="background-color: #ffc107; color: #000; cursor: pointer; margin: 0; font-weight: bold; padding: 12px 25px;">
                                        Restore
                                    </label>
                                    <input type="file" name="backup_file" id="restore_halls" accept=".sql,.csv" style="display: none;" onchange="this.form.submit()">
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Quarter Details Record: Save a SQL dump of all quarter records.
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                <form action="{{ route('settings.backup.quarters') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                                </form>
                                <form action="{{ route('settings.restore.quarters') }}" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return confirm('WARNING: This will replace all Quarter data and cannot be undone. Are you sure?');">
                                    @csrf
                                    <label for="restore_quarters" class="btn-save" style="background-color: #ffc107; color: #000; cursor: pointer; margin: 0; font-weight: bold; padding: 12px 25px;">
                                        Restore
                                    </label>
                                    <input type="file" name="backup_file" id="restore_quarters" accept=".sql,.csv" style="display: none;" onchange="this.form.submit()">
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Officers Details Record: Save a SQL dump of all registered officer records.
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                <form action="{{ route('settings.backup.officers') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                                </form>
                                <form action="{{ route('settings.restore.officers') }}" method="POST" enctype="multipart/form-data" 
                                      onsubmit="return confirm('WARNING: This will replace all Officer data and cannot be undone. Are you sure?');">
                                    @csrf
                                    <label for="restore_officers" class="btn-save" style="background-color: #ffc107; color: #000; cursor: pointer; margin: 0; font-weight: bold; padding: 12px 25px;">
                                        Restore
                                    </label>
                                    <input type="file" name="backup_file" id="restore_officers" accept=".sql,.csv" style="display: none;" onchange="this.form.submit()">
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Hall Booking Applications: Save a SQL dump of hall bookings and hall details.
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.hallbookings') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Scheduled Quarter Applications: Save a SQL dump of all scheduled quarter applications.
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.scheduled') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Family Quarter Applications: Save a SQL dump of all family quarter applications (including
                            marking).
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.family') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Grade Salary Setting: Save a SQL dump of grade salary settings.
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.gradesalary') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Marking Scheme: Save a SQL dump of the marking scheme.
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.markingscheme') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Memo Tables: Save a SQL dump of internal memos.
                        </td>
                        <td style="text-align: center;">
                            <form action="{{ route('settings.backup.memos') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-save" style="background-color: #28a745;">Backup</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br><br>
            <h3 style="text-align: center; color:rgb(255, 136, 0)">Danger Zone</h3>

            <table class="advanced-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Clear all audit log records from the system. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-audit-form" action="{{ route('auditlog.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all audit log records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all hall booking details records from the system. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-bookings-form" action="{{ route('bookings.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all hall booking records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear rejected hall booking application from history. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-rejected-bookings-form" action="{{ route('bookings.clearRejected') }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all rejected hall booking records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear rejected scheduled quarter applications. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-rejected-scheduled-form"
                                action="{{ route('quarters.scheduled.clearRejected') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all rejected scheduled quarter applications? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear rejected family quarter applications. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-rejected-family-form" action="{{ route('quarters.family.clearRejected') }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all rejected family quarter applications? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all resolved internal memos from the system history. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-memos-form" action="{{ route('memos.clearResponded') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all resolved internal memos? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td>Clear all user details records from the system. (This action cannot be undone and will not
                            delete admin users)</td>
                        <td style="text-align: center;">
                            <form id="clear-users-form" action="{{ route('users.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger"
                                    data-confirm="Are you sure you want to clear all non-admin user records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- Confirmation Overlay --}}
    <div id="confirmation-overlay">
        <div class="overlay-content">
            <h3 style="color: #dc3545; margin-bottom: 15px;">Confirm Action</h3>
            <p id="confirmation-message" style="font-size: 1.1em; color: #333; margin-bottom: 25px;"></p>
            <div style="display: flex; justify-content: center; gap: 15px;">
                <button id="confirm-btn" class="btn-danger" style="padding: 10px 20px; font-size: 1em;">Yes, Clear
                    It</button>
                <button id="cancel-btn"
                    style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; font-weight: bold;">Cancel</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const overlay = document.getElementById('confirmation-overlay');
                const message = document.getElementById('confirmation-message');
                const confirmBtn = document.getElementById('confirm-btn');
                const cancelBtn = document.getElementById('cancel-btn');
                const modalTitle = document.querySelector('#confirmation-overlay h3');
                let currentForm = null;

                // Check for Server-Side Modal Messages (Success/Error)
                @if(session('success_modal'))
                    modalTitle.innerText = 'Success';
                    modalTitle.style.color = '#28a745';
                    message.innerText = "{{ session('success_modal') }}";
                    confirmBtn.style.display = 'none'; // Hide action button
                    cancelBtn.innerText = 'Close';
                    overlay.style.display = 'flex';
                @endif

                @if(session('error_modal'))
                    modalTitle.innerText = 'Error';
                    modalTitle.style.color = '#dc3545';
                    message.innerText = "{{ session('error_modal') }}";
                    confirmBtn.style.display = 'none'; // Hide action button
                    cancelBtn.innerText = 'Close';
                    overlay.style.display = 'flex';
                @endif

                document.querySelectorAll('form button[type="submit"]').forEach(button => {
                    button.addEventListener('click', function (e) {
                        if (this.dataset.confirm) {
                            e.preventDefault();
                            currentForm = this.closest('form');
                            message.textContent = this.dataset.confirm;
                            overlay.style.display = 'flex';
                        }
                    });
                });

                confirmBtn.addEventListener('click', function () {
                    if (currentForm) {
                        currentForm.submit();
                    }
                });

                cancelBtn.addEventListener('click', function () {
                    overlay.style.display = 'none';
                    currentForm = null;
                });

                // Close on outside click
                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay) {
                        overlay.style.display = 'none';
                        currentForm = null;
                    }
                });
            });
        </script>
    @endpush
@endsection