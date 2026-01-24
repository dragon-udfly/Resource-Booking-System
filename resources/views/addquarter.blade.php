@extends('layouts.admin_body_layout')

@section('title', 'Add Quarter - District Secretariat Vavuniya')

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
        .form-group input[type="tel"],
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

        .back-button {
            background-color: #6c757d;
        }

        .submit-btn:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        .reset-btn {
            background-color: #6c757d;
            color: white;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="submit-btn" style="background-color: #6c757d; text-decoration: none">Back</a>
        </div>
        <div class="page-header">
            <h2>Add New Quarter</h2>
            <p>Fill in the details below to add a new quarter to the system</p>
        </div>

        <div class="form-container">
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-info">
                <p>Fields marked with <span style="color: #ff0000;">*</span> are required. Please ensure all information is accurate before submitting.</p>
            </div>

            <form action="{{ route('quarters.store') }}" method="POST" onsubmit="return confirm('Are you sure you want to add this quarter?');">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="quarter_type">Quarter Type <span class="required">*</span></label>
                        <select id="quarter_type" name="quarter_type" required>
                            <option value="">Select Quarter Type</option>
                            <option value="NORMAL">Normal</option>
                            <option value="FAMILY">Family</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Quarter Status <span class="required">*</span></label>
                        <select id="status" name="status" required>
                            <option value="">Select status</option>
                            <option value="NOT_ALLOCATED" selected>Not Allocated</option>
                            <option value="OCCUPIED">Occupied</option>
                            <option value="REPAIR">Repair</option>
                            <option value="DEMOLISHED">Demolished</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="old_quarter_no">Old Quarter No.</label>
                        <input type="text" id="old_quarter_no" name="old_quarter_no" placeholder="e.g. SC 01, IRDP 02">
                    </div>
                    <div class="form-group">
                        <label for="new_quarter_no">New Quarter No.</label>
                        <input type="text" id="new_quarter_no" name="new_quarter_no" placeholder="e.g. Q-01 (G-V)">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="location">Location / Address <span class="required">*</span></label>
                        <input type="text" id="location" name="location" placeholder="Enter quarter address" required>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Add Quarter</button>
                    <button type="reset" class="reset-btn">Reset Form</button>
                </div>
            </form>
        </div>
    </section>
@endsection
