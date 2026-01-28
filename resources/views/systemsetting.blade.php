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
            max-width: 800px;
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

        .advanced-table th, .advanced-table td {
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

            {{-- <form action="#" method="POST">
                @csrf
                <div class="settings-group">
                    
                </div>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form> --}}
            <br><br><br>
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
                        <td>Manage Quarters in the system.</td>
                        <td style="text-align: center;">
                            <a href="{{ route('quarters.index') }}" class="btn-save">Manage</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all audit log records from the system. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-audit-form" action="{{ route('auditlog.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to clear all audit log records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all hall details records from the system. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-halls-form" action="{{ route('halls.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to clear all hall records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all hall booking details records from the system. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-bookings-form" action="{{ route('bookings.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to clear all hall booking records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear rejected hall booking application from history. (This action cannot be undone)</td>
                        <td style="text-align: center;">
                            <form id="clear-rejected-bookings-form" action="{{ route('bookings.clearRejected') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to clear all rejected hall booking records? This action cannot be undone.">Clear</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Clear all user details records from the system. (This action cannot be undone and will not delete admin users)</td>
                        <td style="text-align: center;">
                            <form id="clear-users-form" action="{{ route('users.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" data-confirm="Are you sure you want to clear all non-admin user records? This action cannot be undone.">Clear</button>
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
                <button id="confirm-btn" class="btn-danger" style="padding: 10px 20px; font-size: 1em;">Yes, Clear It</button>
                <button id="cancel-btn" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; font-weight: bold;">Cancel</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('confirmation-overlay');
            const message = document.getElementById('confirmation-message');
            const confirmBtn = document.getElementById('confirm-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            let currentForm = null;

            document.querySelectorAll('form button[type="submit"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.dataset.confirm) {
                        e.preventDefault();
                        currentForm = this.closest('form');
                        message.textContent = this.dataset.confirm;
                        overlay.style.display = 'flex';
                    }
                });
            });

            confirmBtn.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
            });

            cancelBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
                currentForm = null;
            });

            // Close on outside click
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                    currentForm = null;
                }
            });
        });
    </script>
    @endpush
@endsection