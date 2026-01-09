@extends('layouts.admin_body_layout')

@section('title', 'District Secretariat - Vavuniya')

@section('page_styles')
    <style>
        div a {
            display: block; 
            padding: 20px 40px; 
            background-color: #007bff; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-size: 1.5em; 
            text-align: center; 
            transition: background-color 0.3s ease;
        }

        .btn-row {
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100%; 
            gap: 80px; 
            display: flex; 
            flex-direction: row; 
            padding: 20px;
        }

        .btn-reset a {
            background-color: #6917dc;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="btn-row">
            <a href="/officers">Officers</a>
            <a href="/quarters">Quarters</a>
            <a href="/halls">Halls</a>
        </div>
        <div class="btn-row btn-reset">
            <a href="/systemsetting">System Setting</a>
        </div>
    </section>
@endsection
