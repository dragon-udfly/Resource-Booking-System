@extends('layouts.normal_body_layout')

@section('title', 'Quarters Application: Family Details')

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
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
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
        
        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 30px;
        }

        .submit-btn, .nav-btn {
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
        .submit-btn:hover { background-color: #0056b3; }
        .nav-btn {
            background-color: #6c757d;
            color: white;
        }
        .nav-btn:hover { background-color: #5a6268; }

        .child-block {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        h3 { font-size: 1.2em; font-weight: bold; margin-bottom: 15px; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px;}

    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="page-header">
            <h2>Quarters Application</h2>
            <p>Step 2 of 4: Family &amp; Children Details</p>
        </div>

        <div class="form-container">
            <form action="/quarters/application/family-details/store" method="POST" id="familyDetailsForm">
                @csrf
                <input type="hidden" name="application_id" value="{{-- {{ $application_id }} --}}"> {{-- Pass this from the controller --}}

                <h3>Spouse & Dependants</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="marital_status">Marital Status</label>
                        <select id="marital_status" name="marital_status">
                            <option value="MARRIED">Married</option>
                            <option value="SINGLE">Single</option>
                            <option value="WIDOWED">Widowed</option>
                            <option value="DIVORCED">Divorced</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dependants">Number of Dependants</label>
                        <select id="dependants" name="dependants">
                            <option value="">Select Number</option>
                            <option value="01_PERSON">01 person</option>
                            <option value="02_PERSONS">02 persons</option>
                            <option value="03_PERSONS">03 persons</option>
                            <option value="04_PERSONS">04 persons</option>
                            <option value="05_OR_ABOVE">05 or above persons</option>
                        </select>
                    </div>
                </div>

                <h3>Spouse Employment Details</h3>
                <div class="form-row">
                     <div class="form-group">
                        <label for="spouse_employed">Is spouse employed in government service?</label>
                        <select id="spouse_employed" name="spouse_employed">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="spouse_designation">Spouse's Designation</label>
                        <input type="text" id="spouse_designation" name="spouse_designation" placeholder="e.g. Clerk">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="spouse_salary">Spouse's Monthly Salary</label>
                        <input type="number" id="spouse_salary" name="spouse_salary" placeholder="LKR">
                    </div>
                     <div class="form-group">
                        <label for="spouse_last_increment">Spouse's Last Salary Increment Date</label>
                        <input type="date" id="spouse_last_increment" name="spouse_last_increment">
                    </div>
                </div>

                <h3>Children Information</h3>
                {{-- Example of one child block. This would be repeated for each child. --}}
                <div class="child-block">
                    <h4>Child 1</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="child_name_1">Child Name</label>
                            <input type="text" id="child_name_1" name="children[0][child_name]">
                        </div>
                        <div class="form-group">
                            <label for="child_age_1">Age</label>
                            <input type="number" id="child_age_1" name="children[0][age]">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="child_grade_1">Grade</label>
                            <input type="text" id="child_grade_1" name="children[0][grade]">
                        </div>
                        <div class="form-group">
                            <label for="child_school_1">School Name</label>
                            <input type="text" id="child_school_1" name="children[0][school]">
                        </div>
                    </div>
                </div>
                {{-- Add more child blocks here as needed --}}

                <div class="button-group">
                    <button type="button" class="nav-btn">Previous</button>
                    <button type="submit" class="submit-btn">Save & Continue</button>
                </div>
            </form>
        </div>
    </section>
@endsection
