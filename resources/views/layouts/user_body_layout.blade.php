<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href='{{ asset('icons/right_logo.png') }}' rel='icon' type='image/png'>

    <style>
        /* ------------------------------------------------ */
        /* COMMON STYLES (Applied to all pages using this layout) */
        /* ------------------------------------------------ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4;
        }

        /* Standard Banner Style */
        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            min-height: 65vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            flex-grow: 1;
            /* Allows banner to expand and fill space so footer sits at bottom */
        }

        /* Styles moved from dashboard.blade.php */
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
            width: 90%;
            /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
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

        .action-btn.approve {
            background-color: #28a745;
        }

        .action-btn.approve:hover {
            background-color: #218838;
        }

        .action-btn.reject {
            background-color: #dc3545;
        }

        .action-btn.reject:hover {
            background-color: #c82333;
        }

        .action-btn.review-btn,
        .action-btn.review-link-btn {
            background-color: #007bff;
        }

        .action-btn.review-btn:hover,
        .action-btn.review-link-btn:hover {
            background-color: #0056b3;
        }
    </style>

    {{-- Slot for page-specific styles --}}
    @yield('page_styles')

</head>

<body>

    {{-- 1. Database Connection Check Overlay --}}
    @include('partials.check_db_overlay')

    {{-- 2. Common Header Partial --}}
    @include('partials.header_nav_user')

    {{-- 3. Logout Confirmation Overlay --}}
    @include('partials.logout_confirmation_overlay')

    {{-- 3. Main Content Slot --}}
    {{-- Child views will inject their content here --}}
    @yield('content')

    {{-- 4. Common Footer Partial --}}
    @include('partials.footer')

    {{-- Slot for page-specific scripts --}}
    @stack('scripts')
</body>

</html>