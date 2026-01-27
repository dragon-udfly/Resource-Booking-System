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
            <form action="{{ route('gradesalary.update') }}" method="POST">
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
                        @foreach($grades as $grade)
                            @php
                                $gradeKey = str_replace([' ', '(', ')', '-'], '_', $grade);
                                $currentMin = $gradeSalarySettings[$grade]->min_salary ?? '';
                                $currentMax = $gradeSalarySettings[$grade]->max_salary ?? '';
                            @endphp
                            <tr>
                                <td>{{ $grade }}</td>
                                <td><input type="number" name="grade_{{ $gradeKey }}_min" value="{{ old('grade_' . $gradeKey . '_min', $currentMin) }}" required></td>
                                <td><input type="number" name="grade_{{ $gradeKey }}_max" value="{{ old('grade_' . $gradeKey . '_max', $currentMax) }}" required></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="button-group">
                    <button type="submit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.form-container form'); // Select the form

        form.addEventListener('submit', function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Show a confirmation dialog
            const confirmation = confirm('Are you sure you want to save these changes to grade salary settings?');

            // If the user confirms, submit the form
            if (confirmation) {
                form.submit();
            }
            // If the user cancels, do nothing (form submission is already prevented)
        });
    });
</script>
@endpush
