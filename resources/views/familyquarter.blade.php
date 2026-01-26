@extends('layouts.normal_body_layout')

@section('title', 'Family Quarters Application - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 1.8em; /* Adjusted for long title */
            margin-bottom: 10px;
        }

        .button-bar {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            width: 90%;
            max-width: 1200px;
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

        .home-btn { background-color: #6c757d; }
        .back-btn { background-color: #007bff; }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px; /* Increased max-width */
            margin-top: 20px;
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
        }
        
        .form-section-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0056b3;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            width: 100%;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
        </div>
        
        <div class="page-header">
            <h2>Requesting accommodation in the government family quarters under the administration of the Ministry of Public Administration in Vavuniya</h2>
        </div>

        <div class="form-container">
            <form action="#" method="POST">
                @csrf
                
                <h3 class="form-section-title">A) Officer Details</h3>
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
                        <label for="designation">Designation</label>
                        <input type="text" id="designation" name="designation">
                    </div>
                </div>

                <h3 class="form-section-title">B) Spouse Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="spouse_name">Name of Spouse</label>
                        <input type="text" id="spouse_name" name="spouse_name">
                    </div>
                    <div class="form-group">
                        <label for="spouse_workplace">Spouse's Place of Work</label>
                        <input type="text" id="spouse_workplace" name="spouse_workplace">
                    </div>
                </div>

                <h3 class="form-section-title">C) Children Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="child_name_1">Child 1 Name</label>
                        <input type="text" id="child_name_1" name="children[0][name]">
                    </div>
                    <div class="form-group">
                        <label for="child_school_1">Child 1 School</label>
                        <input type="text" id="child_school_1" name="children[0][school]">
                    </div>
                </div>

                <h3 class="form-section-title">D) Property Ownership in Vavuniya District</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="has_property">Do you or your spouse own property in Vavuniya?</label>
                        <select id="has_property" name="has_property">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="property_details">If yes, provide details</label>
                        <textarea id="property_details" name="property_details" rows="3"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">E) Previous Stay in Government Quarters</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="previous_stay">Have you stayed in government quarters before?</label>
                        <select id="previous_stay" name="previous_stay">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="previous_stay_details">If yes, provide details</label>
                        <textarea id="previous_stay_details" name="previous_stay_details" rows="3"></textarea>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn" style="background-color: #007bff;">Submit</button>
                    <button type="reset" class="btn" style="background-color: #6c757d;">Reset</button>
                </div>
            </form>
        </div>
    </section>
@endsection
