@extends('layouts.admin_body_layout')

@section('title', 'Quarters - District Secretariat Vavuniya')

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

        .action-btn:nth-of-type(3) { /* Delete button */
            background-color: #dc3545;
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
            <h2>Quarters List</h2>
            <p>Manage officers by modifying or deleting entries</p>
        </div>

        <!-- Add Officer Button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="/addquarter" class="add-officer-btn">Add Quarters</a>
        </div>

        <!-- Officer Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Quarter ID</th>
                    <th>Title</th>
                    <th>Grade</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>V_DSQ0001</td>
                    <td>Goverment Agent Quarter</td>
                    <td>A</td>
                    <td>Home Affairs</td>
                    <td>Occupied</td>
                    <td>
                        <button class="action-btn" onclick=viewOfficer()>View</button>
                        <button class="action-btn" onclick="modifyOfficer()">Modify</button>
                        <button class="action-btn" onclick="deleteOfficer()">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>V_DSQ0003</td>
                    <td>Additional Goverment Agent Quarter</td>
                    <td>A</td>
                    <td>Home Affairs</td>
                    <td>Occupied</td>
                    <td>
                        <button class="action-btn" onclick=viewOfficer()>View</button>
                        <button class="action-btn" onclick="modifyOfficer()">Modify</button>
                        <button class="action-btn" onclick="deleteOfficer()">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>V_DSQ0002</td>
                    <td>Administrative Officer Block</td>
                    <td>1</td>
                    <td>Home Affairs</td>
                    <td>Occupied</td>
                    <td>
                        <button class="action-btn" onclick=viewOfficer()>View</button>
                        <button class="action-btn" onclick="modifyOfficer()">Modify</button>
                        <button class="action-btn" onclick="deleteOfficer()">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
@endsection

@push('scripts')
    <script>
        function viewQuarter(){
            window.location.href= "/viewofficer";
        }

        function modifyQuarter() {
            // add code
        }

        function deleteQuarter() {
            // add code
        }
    </script>
@endpush
