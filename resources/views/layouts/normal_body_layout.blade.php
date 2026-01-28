<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            min-height: 100vh; /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4;
            position: relative; /* Added for absolute positioning context */
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
            flex-grow: 1; /* Allows banner to expand and fill space so footer sits at bottom */
        }
    </style>

    {{-- Slot for page-specific styles --}}
    @yield('page_styles')

</head>
<body>
    
    {{-- 1. Database Connection Check Overlay --}}
    @include('partials.check_db_overlay')

    {{-- 2. Common Header Partial --}}
    @include('partials.header')

    @yield('content')

    {{-- 4. Common Footer Partial --}}
    @include('partials.footer')

    {{-- Slot for page-specific scripts --}}
    @stack('scripts')
</body>
</html>