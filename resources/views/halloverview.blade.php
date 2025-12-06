@extends('layouts.normal_body_layout')

@section('title', 'Hall Overview - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .content-area {
            padding: 40px 20px;
            background-color: #f4f7f6;
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
        .halls-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .hall-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hall-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .hall-card h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #0056b3;
        }
        .hall-card p {
            font-size: 1em;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .hall-card .capacity {
            font-weight: bold;
            color: #007bff;
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
        <div class="page-header">
            <h1>Available Halls</h1>
            <p>Here is an overview of the halls available for booking.</p>
        </div>

        @if($halls->count() > 0)
            <div class="halls-container">
                @foreach($halls as $hall)
                    <div class="hall-card">
                        <h2>{{ $hall->hall_type }}</h2>
                        <p>{{ $hall->description }}</p>
                        <p class="capacity">Capacity: {{ $hall->capacity }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-halls">No halls are available at the moment.</p>
        @endif

        <div class="button-bar">
            <a href="{{ route('halls.book') }}" class="action-button">Book Hall</a>
        </div>
    </section>
@endsection
