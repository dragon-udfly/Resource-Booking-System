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

        th, td {
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
        <div class="page-header">
            <h2>Grade Salary Settings</h2>
            <br />
            <p>Change Salary Range for Service Grade</p>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                @csrf
                @method('PATCH') {{-- Use PATCH method for updates --}}

                <table>
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Minimum Salary</th>
                            <th>Maximum Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1 (G I)</td>
                            <td><input type="number" name="grade_1_min" value="30000"></td>
                            <td><input type="number" name="grade_1_max" value="45000"></td>
                        </tr>
                        <tr>
                            <td>2 (G II)</td>
                            <td><input type="number" name="grade_2_min" value="45001"></td>
                            <td><input type="number" name="grade_2_max" value="60000"></td>
                        </tr>
                        <tr>
                            <td>3 (G III)</td>
                            <td><input type="number" name="grade_3_min" value="60001"></td>
                            <td><input type="number" name="grade_3_max" value="75000"></td>
                        </tr>
                        <tr>
                            <td>4 (G IV)</td>
                            <td><input type="number" name="grade_4_min" value="75001"></td>
                            <td><input type="number" name="grade_4_max" value="90000"></td>
                        </tr>
                        <tr>
                            <td>5 (G V)</td>
                            <td><input type="number" name="grade_5_min" value="90001"></td>
                            <td><input type="number" name="grade_5_max" value="105000"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="button-group">
                    <button type="submit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </section>
@endsection
