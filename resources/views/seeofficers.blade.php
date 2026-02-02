@extends('layouts.user_body_layout')

@section('title', 'Officers - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        /* Table styles */
        table {
            width: 90%;
            /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
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
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Officers List</h2>

                <!-- Grade Salary Table (Read Only) -->
                <div style="margin: 20px auto; width: 100%; overflow-x: auto;">
                    <p>Grade Salary Details</p>
                    <table
                        style="width: 100%; border-collapse: collapse; margin-bottom: 20px; box-shadow: none; background-color: transparent;">
                        <thead>
                            <tr>
                                <th
                                    style="background-color: #e9ecef; color: #495057; border: 1px solid #dee2e6; padding: 8px; text-align: center;">
                                    Grade</th>
                                <th
                                    style="background-color: #e9ecef; color: #495057; border: 1px solid #dee2e6; padding: 8px; text-align: center;">
                                    Minimum Salary</th>
                                <th
                                    style="background-color: #e9ecef; color: #495057; border: 1px solid #dee2e6; padding: 8px; text-align: center;">
                                    Maximum Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($grades) && isset($gradeSalarySettings))
                                @foreach($grades as $grade)
                                    @php
                                        $currentMin = $gradeSalarySettings[$grade]->min_salary ?? '-';
                                        $currentMax = $gradeSalarySettings[$grade]->max_salary ?? '-';
                                    @endphp
                                    <tr>
                                        <td
                                            style="border: 1px solid #dee2e6; padding: 8px; text-align: center; background-color: #fff;">
                                            {{ $grade }}</td>
                                        <td
                                            style="border: 1px solid #dee2e6; padding: 8px; text-align: center; background-color: #fff;">
                                            {{ $currentMin }}</td>
                                        <td
                                            style="border: 1px solid #dee2e6; padding: 8px; text-align: center; background-color: #fff;">
                                            {{ $currentMax }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 10px;">No grade salary details
                                        available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <p>Details of all officers in the system.</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->designation }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact_number }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">No officers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
    </section>
@endsection