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

        #developers-link-container {
            position: absolute;
            bottom: 65px; /* Adjust as needed to be above the footer */
            left: 5px;
            text-align: left;
            padding: 10px; /* Add some padding for better appearance */
            z-index: 1000; /* Ensure it's above other content */
        }
        #developers-link-container p {
            margin: 0; /* Remove default paragraph margin */
        }
        #developers-link-container a {
            color: #0056b3; /* Link color */
            text-decoration: none;
        }
        #developers-link-container a:hover {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="content-area">
            <h1 class="main-title">Welcome to the Hall and Quarters Booking System</h1>
            <p class="sub-text">Please log in to approve hall booking and quarter reservation applications.</p>
            
            <!-- Login Button -->
            <a href="/login" class="action-button">System Login</a>
            
            <!-- Book Hall Button -->
            <a href="{{ route('halls.schedule') }}" class="action-button" style="margin-left: 10px;">Book Hall</a>
            
            <!-- Book Quarter Button -->
            <a href="{{ route('quarterapplication') }}" class="action-button" style="margin-left: 10px;">Book Quarters</a>
        </div>
    </section>

    <div id="developers-link-container">
        <p title="Click Me to see Developers">
            <a href="/developers">> Developers</a>
        </p>
    </div>
@endsection