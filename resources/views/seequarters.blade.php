@extends('layouts.user_body_layout')

@section('title', 'See Quarters - District Secretariat Vavuniya')

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

        table {
            width: 90%; /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
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
        /* Specific back button styles */
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
    <section class="banner">
         <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
            <a href="{{ route('occupantdetails') }}" class="btn back-button" style="background-color:rgb(38, 209, 11)">Occupant Details</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Quarters Details</h2>
        </div>

        <table id="quarter-details">
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>Quarters No. (old)</th>
                    <th>Quarters No. (new)</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Allowed Gender</th>
                    <th>Special Notice</th>
                    <th>Number of Allowed/Max Occupants</th>
                    <th>Number of Current Occupants</th>
                    <th>Vacancies in Quarter (Not Allocated)</th>
                </tr>
            </thead>
            <tbody>
                @if($quarters->isEmpty())
                    <tr>
                        <td colspan="11" style="text-align: center;">No quarters found.</td>
                    </tr>
                @else
                    @foreach($quarters as $index => $quarter)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $quarter->old_quarter_no }}</td>
                            <td>{{ $quarter->new_quarter_no }}</td>
                            <td>{{ $quarter->location }}</td>
                            <td>{{ $quarter->quarter_type }}</td>
                            <td>{{ $quarter->status }}</td>
                            <td>{{ $quarter->allowed_gender }}</td>
                            <td>{{ $quarter->special_notice }}</td>
                            <td>{{ $quarter->occupant_number }}</td>
                            <td>{{ $quarter->current_occupant_number }}</td>
                            <td>{{ $quarter->occupant_number - $quarter->current_occupant_number }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </section>
@endsection