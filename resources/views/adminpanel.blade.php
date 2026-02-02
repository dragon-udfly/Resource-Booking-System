@extends('layouts.admin_body_layout')

@section('title', 'District Secretariat - Vavuniya')

@section('page_styles')
    <style>
        .btn-row a {
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

        .h1-class {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: rgb(6, 4, 60)
        }

        .h2-class {
            text-align: center;
            margin-bottom: 20px;
            color: rgb(90, 90, 98);
            font-size: 20px;
        }
    </style>
@endsection

@section('content')
    <section class="banner">
        <br />
        <h1 class="h1-class">Admin Dashboard</h1>
        <br />
        <p class="h2-class">Manage system resources</p>
        <div class="btn-row">
            <a href="/officers">Officers</a>
            <a href="/quarters">Quarters</a>
            <a href="/halls">Halls</a>
        </div>
    </section>
@endsection