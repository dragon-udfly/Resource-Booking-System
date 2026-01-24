@extends('layouts.normal_body_layout')

@section('title', 'Quarters Application - District Secretariat Vavuniya')

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
        .form-group input[type="email"],
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
            resize: vertical;
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
            transition: background-color 0.3s ease, transform 0.2s ease;
            background-color: #007bff;
            color: white;
        }

        .submit-btn:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="page-header">
            <h2>Quarters Application</h2>
            <p>Step 1 of 4: Officer Details</p>
        </div>

        <div class="form-container">
            <div class="form-info">
                <p>Please fill in your details accurately.</p>
            </div>

            <form action="/quarters/application/store" method="POST" id="applicationForm">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="quarter_type">Quarter Type</label>
                        <select id="quarter_type" name="quarter_type">
                            <option value="FAMILY">Family</option>
                            <option value="NORMAL">Normal</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="officer_name">Name of Officer</label>
                        <input type="text" id="officer_name" name="officer_name">
                    </div>
                    <div class="form-group">
                        <label for="nic">NIC Number</label>
                        <input type="text" id="nic" name="nic">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation">
                    </div>
                    <div class="form-group">
                        <label for="service_grade">Service and Grade</label>
                        <select id="service_grade" name="service_grade">
                            <option disabled selected value="">Select Grade</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="5a">5A</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="department">Department Selection</label>
                        <select id="department" name="department">
                            <option disabled selected value="">Select Department</option>
                            <option value="MINISTRY_HOME_AFFAIRS">Ministry of Home Affairs</option>
                            <option value="DISTRICT_DIVISIONAL_SECRETARIAT">District & Divisional Secretariats</option>
                            <option value="OTHER_OFFICERS">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex-basis: 100%;">
                        <label for="permanent_address">Permanent Address</label>
                        <textarea id="permanent_address" name="permanent_address" rows="3"></textarea>
                    </div>
                    <div class="form-group" style="flex-basis: 100%;">
                        <label for="temporary_address">Temporary Address</label>
                        <textarea id="temporary_address" name="temporary_address" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="distance_of_residency">Distance of Residency</label>
                        <select id="distance_of_residency" name="distance_of_residency">
                            <option disabled selected value="">Select Distance Range</option>
                            <option value="OUT_DISTRICT_ABOVE_100KM">Out District - above 100km</option>
                            <option value="OUT_DISTRICT_51_TO_100KM">Out District - between 51km and 100km</option>
                            <option value="OUT_DISTRICT_26_TO_50KM">Out District - between 26km and 50km</option>
                            <option value="OUR_DISTRICT_BELOW_25KM">Our District - Below 25km</option>
                            <option value="OUT_URBAN_ABOVE_30KM">Out of Urban Council Area above 30km</option>
                            <option value="OUT_URBAN_0_TO_30KM">Out of Urban Council Area between 00km and 30km</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_salary">Monthly Salary</label>
                        <input type="number" id="monthly_salary" name="monthly_salary">
                    </div>
                    <div class="form-group">
                        <label for="date_of_last_salary_increment">Last Increment Date</label>
                        <input type="date" id="date_of_last_salary_increment" name="date_of_last_salary_increment">
                    </div>
                    <div class="form-group">
                        <label for="duty_assumed_date">Date of Assumption of Duties in Vavuniya</label>
                        <input type="date" id="duty_assumed_date" name="duty_assumed_date">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Telephone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Save & Continue</button>
                </div>
            </form>
        </div>
    </section>
@endsection
