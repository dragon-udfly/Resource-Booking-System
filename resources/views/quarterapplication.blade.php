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

        .button-bar {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            width: 90%;
            max-width: 900px;
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
            transition: background-color 0.3s ease;
        }

        .home-btn { background-color: #6c757d; } /* Grey */
        .back-btn { background-color: #007bff; } /* Blue */
        .details-btn { background-color: #28a745; } /* Green */
        
        .btn:hover {
            opacity: 0.9;
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

        .child-block {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            flex-basis: 100%;
        }
        h3 { font-size: 1.2em; font-weight: bold; margin-bottom: 15px; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; width:100%;}
    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px; display: flex; gap: 15px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
            <a href="#" class="btn back-btn" style="background-color:#28a745">Details</a>
        </div>
        <div class="page-header">
            <h2>Quarters Application</h2>
            <p>Please fill in all details accurately.</p>
        </div>

        <div class="form-container">
            <form action="{{ route('quarterapplication.store') }}" method="POST" id="applicationForm">
                @csrf
                
                <h3>Officer Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="quarter_type">Quarter Type</label>
                        <select id="quarter_type" name="quarter_type" required>
                            <option value="">Select Quarter Type</option>
                            <option value="Family">Family</option>
                            <option value="Scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="officer_name">Name of Officer</label>
                        <input type="text" id="officer_name" name="officer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="nic">NIC Number</label>
                        <input type="text" id="nic" name="nic" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation" required>
                    </div>
                    <div class="form-group">
                        <label for="service_grade">Service and Grade</label>
                        <select id="service_grade" name="service_grade" required>
                            <option value="">Select Grade</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="5A">5A</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex-basis: 100%;">
                        <label for="permanent_address">Permanent Address</label>
                        <textarea id="permanent_address" name="permanent_address" rows="3" required></textarea>
                    </div>
                    <div class="form-group" style="flex-basis: 100%;">
                        <label for="temporary_address">Temporary Address</label>
                        <textarea id="temporary_address" name="temporary_address" rows="3" required></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_salary">Monthly Salary</label>
                        <input type="number" id="monthly_salary" name="monthly_salary" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_assumption_of_duties">Date of Assumption of Duties</label>
                        <input type="date" id="date_of_assumption_of_duties" name="date_of_assumption_of_duties" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" required>
                    </div>
                </div>

                {{-- Family Quarter Application Fields --}}
                <div id="familyQuarterFields" style="display: none;">
                    <br /><hr /><br />
                    <h3>Family Quarter Specific Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="f_dob">Date of Birth</label>
                            <input type="date" id="f_dob" name="f_dob">
                        </div>
                        <div class="form-group">
                            <label for="f_date_of_last_salary_increment">Date of Last Salary Increment</label>
                            <input type="date" id="f_date_of_last_salary_increment" name="f_date_of_last_salary_increment">
                        </div>
                        <div class="form-group">
                            <label for="f_marital_status">Marital Status</label>
                            <select id="f_marital_status" name="f_marital_status">
                                <option value="">Select Marital Status</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="f_is_spouse_employed">Is spouse employed?</label>
                            <select id="f_is_spouse_employed" name="f_is_spouse_employed">
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f_spouse_designation">Spouse's Designation</label>
                            <input type="text" id="f_spouse_designation" name="f_spouse_designation">
                        </div>
                        <div class="form-group">
                            <label for="f_spouse_department_office">Spouse's Department/Office</label>
                            <input type="text" id="f_spouse_department_office" name="f_spouse_department_office">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="f_spouse_monthly_salary">Spouse's Monthly Salary</label>
                            <input type="number" id="f_spouse_monthly_salary" name="f_spouse_monthly_salary">
                        </div>
                        <div class="form-group">
                            <label for="f_spouse_last_increment_date">Spouse's Last Increment Date</label>
                            <input type="date" id="f_spouse_last_increment_date" name="f_spouse_last_increment_date">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="f_children_details_description">Children Details (Name, Age, School, Grade etc.)</label>
                            <textarea id="f_children_details_description" name="f_children_details_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="f_property_ownership_details">Property Ownership Details (within 5km of Vavuniya town)</label>
                            <textarea id="f_property_ownership_details" name="f_property_ownership_details" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="f_previous_government_quarter_duration">Previous Government Quarter Duration (in years)</label>
                            <input type="number" id="f_previous_government_quarter_duration" name="f_previous_government_quarter_duration">
                        </div>
                    </div>
                </div>

                {{-- Scheduled Quarter Application Fields --}}
                <div id="scheduledQuarterFields" style="display: none;">
                    <br /><hr /><br />
                    <h3>Scheduled Quarter Specific Details</h3>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="sq_transfered_officer_priority_request">Transferred Officer Priority Request (if applicable)</label>
                            <textarea id="sq_transfered_officer_priority_request" name="sq_transfered_officer_priority_request" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="sq_night_duty_priority_request">Night Duty Priority Request (if applicable)</label>
                            <textarea id="sq_night_duty_priority_request" name="sq_night_duty_priority_request" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="sq_other_special_reason_priority_request">Other Special Reason Priority Request</label>
                            <textarea id="sq_other_special_reason_priority_request" name="sq_other_special_reason_priority_request" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex-basis: 100%;">
                            <label for="sq_property_ownership_details">Property Ownership Details</label>
                            <textarea id="sq_property_ownership_details" name="sq_property_ownership_details" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Submit Application</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quarterTypeSelect = document.getElementById('quarter_type');
            const familyQuarterFields = document.getElementById('familyQuarterFields');
            const scheduledQuarterFields = document.getElementById('scheduledQuarterFields');

            function toggleQuarterFields() {
                const selectedType = quarterTypeSelect.value;
                if (selectedType === 'Family') {
                    familyQuarterFields.style.display = 'block';
                    scheduledQuarterFields.style.display = 'none';
                    setRequired(familyQuarterFields, true);
                    setRequired(scheduledQuarterFields, false);
                } else if (selectedType === 'Scheduled') {
                    familyQuarterFields.style.display = 'none';
                    scheduledQuarterFields.style.display = 'block';
                    setRequired(familyQuarterFields, true);
                    setRequired(scheduledQuarterFields, false);
                } else {
                    familyQuarterFields.style.display = 'none';
                    scheduledQuarterFields.style.display = 'none';
                    setRequired(familyQuarterFields, false);
                    setRequired(scheduledQuarterFields, false);
                }
            }

            function setRequired(element, isRequired) {
                element.querySelectorAll('input, select, textarea').forEach(field => {
                    if (field.name !== '_token') { // Don't set required for _token
                        if (isRequired) {
                            field.setAttribute('required', 'required');
                        } else {
                            field.removeAttribute('required');
                        }
                    }
                });
            }

            quarterTypeSelect.addEventListener('change', toggleQuarterFields);

            // Initial call to set the correct fields based on default selection (if any)
            toggleQuarterFields();
        });
    </script>
@endsection