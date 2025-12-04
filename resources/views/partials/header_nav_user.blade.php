<style>
    /* Header-Specific Styles */
    .header {
        background-color: #f8f9fa;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-bottom: 3px solid #ddd;
    }

    .header-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px 20px; /* Added padding to main header area */
    }

    .logo-left {
        width: 110px;
        height: 22vh;
        margin-left: 70px;
    }

    .header-content {
        flex: 1;
        text-align: center;
        padding: 0 10px;
    }

    .header-content h1 {
        font-size: 40px;
        font-weight: bold;
        color: #000;
        padding-bottom: 20px;
    }

    .header-content h2 {
        font-size: 25px;
        font-weight: normal;
        color: #333;
    }

    .logo-right {
        width: 130px;
        height: 22vh;
        margin-right: 70px;
    }

    /* Navigation Bar Styles */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 10px 20px;
        background-color: #e9ecef; /* Light grey background for navbar */
        border-top: 1px solid #dee2e6;
    }

    .navbar ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
    }

    .navbar li {
        margin-right: 20px;
    }

    .navbar li:last-child {
        margin-right: 0;
    }

    .navbar a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .navbar a:hover {
        color: #0056b3;
    }

    .navbar-right {
        margin-left: auto; /* Pushes right items to the right */
    }
</style>

<!-- HEADER HTML -->
<header class="header">
    <div class="header-main">
        <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
        <div class="header-content">
            <h1>District Secretariat - Vavuniya</h1>
            <h2>Hall and Quarters Booking System - Officer</h2>
        </div>
        <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
    </div>
    <nav class="navbar">
        <ul class="navbar-left">
           <li id="nav-preference"><a href="{{ route('preference') }}">Preference</a></li>
           <li id="nav-dashboard"><a href="/dashboard">Dashboard</a></li>
            @if(Auth::user()->hasPermissionTo('view_officers'))
                <li id="nav-officers"><a href="{{ route('seeofficers') }}">Officers</a></li>
            @endif
            <li id="nav-halls"><a href="/seehalls">Halls</a></li>
            <li id="nav-quarter"><a href="#">Quarters</a></li>
            <li id="nav-audit-log"><a href="/seeauditlog">Audit Log</a></li>
        </ul>
        <ul class="navbar-right">
            <li id="loggedin_user" style="color: rgb(6, 4, 60); font-weight: bold">
                @auth
                <span id="designation">{{ Auth::user()->designation }}</span>, 
                <span id="first_name">{{ Auth::user()->first_name }}</span>
                @endauth
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #007bff; font-weight: bold; cursor: pointer; font-size: 1em; padding: 0;">Log Out</button>
                </form>
            </li>
        </ul>
    </nav>
</header>