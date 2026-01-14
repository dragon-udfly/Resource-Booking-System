@extends('layouts.normal_body_layout')

@section('title', 'Welcome - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        /* HOMEPAGE UNIQUE STYLES (Content and Buttons) */
        .content-area { 
            text-align: center; 
            padding-top: 100px;
        }
        .main-title { 
            color: rgb(6, 4, 60); 
            font-size: 3em; 
        }
        .sub-text { 
            color: rgb(71, 66, 85); 
            font-size: 1.2em; 
            margin-top: 20px; 
        }
        .action-button {
            display: inline-block; 
            margin-top: 30px; 
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
    <section class="banner">
        <div class="content-area">
            <h1 class="main-title">Welcome to the Hall and Quarters Booking System</h1>
            <p class="sub-text">Hall booking and quarter reservation applications.</p>
            
            <!-- Book Hall Button -->
            <a href="{{ route('halls.schedule') }}" class="action-button" style="margin-left: 10px;">Book Hall</a>

            <!-- Book Quarter Button -->
            <a href="/book-quarter" class="action-button" style="margin-left: 10px;">Book Quarters</a>
        </div>
    </section>
@endsection
