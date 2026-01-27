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
        
        .btn:hover {
            opacity: 0.9;
        }
        /* New button styles for booking types */
        .book-quarter-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #25a309; /* Green for booking */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 15px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .book-quarter-btn:hover {
            background-color: #218838;
        }

        /* Table styles */
        table {
            width: 90%; /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #f1f1f1
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

    </style>
@endsection

@section('content')
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px; display: flex; gap: 15px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; width: 90%; max-width: 900px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header">
            <h2>Book Quarters</h2>
            <p>Applications for requesting accommodation in the government quarters administered by the Ministry of Public Administration in Vavuniya</p>
            <br />
            <p>Select a quarter type to proceed with booking or view available quarters.</p>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <a href="{{ route('familyquarter') }}" class="book-quarter-btn">Book Family Quarters</a>
            <a href="{{ route('scheduledquarter') }}" class="book-quarter-btn">Book Scheduled Quarters</a>
        </div>

        <div class="form-container">
            <h2 style="text-align: center; color: rgb(6, 4, 60); font-weight: bold; margin-bottom: 20px;">Overview of Quarters</h2>
            <table id="quarters-overview">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Old Quarters No.</th>
                        <th>New Quarters No.</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Allowed/Max Occupants</th>
                        <th>Allowed Gender</th>
                        <th>Vacancies in Quarter (Not Allocated)</th>
                        <th>Special Notice</th>
                    </tr>
                </thead>
                <tbody>
                    @if($quarters->isEmpty())
                        <tr>
                            <td colspan="7" style="text-align: center;">No quarters found.</td>
                        </tr>
                    @else
                        @foreach($quarters as $index => $quarter)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $quarter->old_quarter_no }}</td>
                                <td>{{ $quarter->new_quarter_no }}</td>
                                <td>{{ $quarter->quarter_type }}</td>
                                <td>{{ $quarter->status }}</td>
                                <td>{{ $quarter->occupant_number }}</td>
                                <th>{{ $quarter->allowed_gender }}</td>
                                <td>{{ $quarter->occupant_number - ($quarter->current_occupant_number ?? 0) }}</td>
                                <td>{{ $quarter->special_notice }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection