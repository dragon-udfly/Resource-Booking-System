@extends('layouts.user_body_layout')

@section('title', 'Halls - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        /* Table styles */
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
        /* Generic button styles */
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
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Halls List</h2>
            <p>Details of all halls in the system.</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hall ID</th>
                    <th>Hall Type</th>
                    <th>Capacity</th>
                    <th>Description</th>
                    <th>Hall Status</th>
                    <th>Special Notice</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($halls as $index => $hall)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $hall->hall_id }}</td>
                        <td>{{ $hall->hall_type }}</td>
                        <td>{{ $hall->capacity }}</td>
                        <td>{{ $hall->description }}</td>
                        <td>{{ $hall->current_state }}</td>
                        <td>{{ $hall->special_notice }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No halls found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection