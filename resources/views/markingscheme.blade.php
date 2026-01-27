@extends('layouts.admin_body_layout')

@section('title', 'Marking Scheme')

@section('page_styles')
    <style>
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
        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
<div class="banner">
    <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px; margin-top: 20px;">
        <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
    </div>
    <div class="container" style="width: 90%; max-width: 900px; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin: 40px auto;">
        <form action="{{ route('marking-scheme.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h2 style="text-align: center; color: #333; margin-bottom: 10px;">Update Marking Scheme Values</h2>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">Adjust the marks awarded for each category below.</p>

            <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th style="padding: 12px;">Title (Section)</th>
                        <th style="padding: 12px;">Option (Criteria)</th>
                        <th style="padding: 12px;">Defined Mark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3"><strong>1. Department</strong></td>
                        <td>Ministry of Home Affairs</td>
                        <td><input type="number" name="marks[1.1]" value="20" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>District & Divisional Secretariats</td>
                        <td><input type="number" name="marks[1.2]" value="15" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>Other Dept.</td>
                        <td><input type="number" name="marks[1.3]" value="10" style="width: 100%; padding: 8px;"></td>
                    </tr>

                    <tr>
                        <td rowspan="6"><strong>2. Date of Application</strong></td>
                        <td>Above 06 years</td>
                        <td><input type="number" name="marks[2.1]" value="20" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>05 years</td>
                        <td><input type="number" name="marks[2.2]" value="15" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>04 years</td>
                        <td><input type="number" name="marks[2.3]" value="12" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>03 years</td>
                        <td><input type="number" name="marks[2.4]" value="9" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>02 years</td>
                        <td><input type="number" name="marks[2.5]" value="6" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>01 year</td>
                        <td><input type="number" name="marks[2.6]" value="3" style="width: 100%; padding: 8px;"></td>
                    </tr>

                    <tr>
                        <td rowspan="6"><strong>3. Dependents</strong></td>
                        <td>05 or Above persons</td>
                        <td><input type="number" name="marks[3.1]" value="17" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>04 persons</td>
                        <td><input type="number" name="marks[3.2]" value="12" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>03 persons</td>
                        <td><input type="number" name="marks[3.3]" value="9" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>02 persons</td>
                        <td><input type="number" name="marks[3.4]" value="6" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>01 person</td>
                        <td><input type="number" name="marks[3.5]" value="3" style="width: 100%; padding: 8px;"></td>
                    </tr>
                    <tr>
                        <td>Disability Bonus</td>
                        <td><input type="number" name="marks[3.6]" value="3" style="width: 100%; padding: 8px;"></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" style="padding: 12px 25px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
                    Update Marking Scheme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection