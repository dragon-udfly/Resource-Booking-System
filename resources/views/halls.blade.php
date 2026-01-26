@extends('layouts.admin_body_layout')

@section('title', 'Halls - District Secretariat Vavuniya')

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

        .add-officer-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745; /* Green for add button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .add-officer-btn:hover {
            background-color: #218838;
        }

        .action-btn {
            padding: 8px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .action-btn:nth-of-type(1) { /* View button */
            background-color: #007bff;
        }

        .action-btn:nth-of-type(2) { /* Modify button */
            background-color: #ffc107;
            color: #333;
        }

        .action-btn:nth-of-type(3) { /* Delete button */
            background-color: #dc3545;
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
    <section class="banner">
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; width: 90%; max-width: 900px;">
                {{ session('success') }}
            </div>
        @endif
         <div style="width: 90%; max-width: 900px; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="btn back-button">Back</a>
        </div>
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Halls List</h2>
            <p>Manage Halls by modifying or deleting entries</p>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="{{ route('halls.create') }}" class="add-officer-btn">Add Hall</a>
        </div>

        <table id="hall-details">
            <thead>
                <tr>
                    <th id="item_number">No</th>
                    <th id="hall_id">Hall ID</th>
                    <th id="hall_type">Hall Type</th>
                    <th id="capacity">Capacity</th>
                    <th id="description">Specail Notice</th>
                    <th id="current_state">Hall Status</th>
                    <th id="booking_state">Booking Status</th>
                    <th id="date_created">Date Created</th>
                    <th id="date_modified">Date Modified</th>
                    <th id="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($halls->isEmpty())
                    <tr>
                        <td colspan="10" style="text-align: center;">No halls found.</td>
                    </tr>
                @else
                    @foreach($halls as $index => $hall)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hall->hall_id }}</td>
                            <td>{{ $hall->hall_type }}</td>
                            <td>{{ $hall->capacity }}</td>
                            <td>{{ $hall->special_notice }}</td>
                            <td>{{ $hall->current_state }}</td>
                            <td>{{ $hall->booking_state }}</td>
                            <td>{{ \Carbon\Carbon::parse($hall->date_created)->format('Y-m-d h:i A') }}</td>
                            <td>{{ $hall->date_modified ? \Carbon\Carbon::parse($hall->date_modified)->format('Y-m-d h:i A') : 'N/A' }}</td>
                            <td>
                                <button class="action-btn" onclick="modifyHall('{{ $hall->hall_id }}')">Modify</button>
                                <button class="action-btn" onclick="deleteHall('{{ $hall->hall_id }}')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </section>
@endsection

@push('scripts')
    <script>
        function modifyHall(hallId) {
            window.location.href = '/halls/' + hallId + '/edit';
        }

        function deleteHall(hallId) {
            if (confirm('Are you sure you want to delete this hall? This action cannot be undone.')) {
                // Create a new form element
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/halls/' + hallId; // The route will be /halls/{hall}

                // Add CSRF token
                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method spoofing for DELETE
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Append form to the body and submit
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
