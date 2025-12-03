@extends('layouts.db_layout')

@section('content')
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        <div style="text-align: center; padding-top: 100px;">
            <h1 style="color: rgb(6, 4, 60); font-size: 3em;">Welcome to the Resource Booking System</h1>
            <p style="color: rgb(71, 66, 85); font-size: 1.2em; margin-top: 20px;">Please log in to approve hall booking and quarter reservation applications.</p>
            <a href="/login" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em;">Login</a>
            <a href="{{ route('halls.schedule') }}" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em; margin-left: 10px;">Book Hall</a>
            <a href="/book-quarter" style="display: inline-block; margin-top: 30px; padding: 15px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 1.2em; margin-left: 10px;">Book Quarter</a>

        </div>
    </section>
@endsection