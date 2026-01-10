@extends('layouts.normal_body_layout')

@section('title', 'Hall Overview - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .content-area {
            padding: 40px 20px;
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            flex-grow: 1; /* Ensure it fills available space to push footer down */
        }
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-header h1 {
            font-size: 2.8em;
            color: rgb(6, 4, 60);
            font-weight: bold;
        }
        .page-header p {
            font-size: 1.2em;
            color: #555;
            margin-top: 10px;
        }
        .content-area table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .content-area th, .content-area td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        .content-area th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        .content-area tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .content-area tr:hover {
            background-color: #f1f1f1;
        }
        .status-unavailable {
            color: #dc3545;
            font-weight: bold;
        }
        .no-halls {
            text-align: center;
            font-size: 1.2em;
            color: #555;
        }
        .button-bar {
            text-align: center;
            margin-top: 40px;
        }
        .action-button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .action-button:hover {
            background-color: #0056b3;
            transform: scale(1.02);
        }
    </style>
@endsection

@section('content')
    <section class="content-area">
        <div style="width: 90%; margin: 0 auto; text-align: left; margin-bottom: 20px;">
            <a href="#" onclick="history.back(); return false;" class="action-button" style="background-color: #6c757d; padding: 10px 20px; font-size: 1em; font-weight: bold;">Back</a>
            <a href="/" class="action-button" style="background-color: #6c757d; padding: 10px 20px; font-size: 1em; font-weight: bold;">Home</a>
        </div>

        <div class="page-header">
            <h1>Halls Overview</h1>
            <p>Here is an overview of the halls included in the system.</p>
        </div>

        @if($halls->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Hall Type</th>
                        <th>Description</th>
                        <th>Current State</th>
                        <th>Special Notice</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($halls as $hall)
                        <tr>
                            <td>{{ $hall->hall_type }}</td>
                            <td>{{ $hall->description }}</td>
                            <td class="{{ $hall->current_state === 'unavailable' ? 'status-unavailable' : '' }}">
                                {{ ucfirst($hall->current_state) }}
                            </td>
                            <td>{{ $hall->special_notice }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-halls">No halls are available at the moment.</p>
        @endif

    </section>
@endsection
