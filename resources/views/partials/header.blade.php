<style>
    /* Header-Specific Styles */
    .header {
        background-color: #f8f9fa;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #ddd;
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
</style>

<!-- HEADER HTML -->
<header class="header">
    <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
    <div class="header-content">
        <h1>District Secretariat - Vavuniya</h1>
        <h2>Hall and Quarters Booking System</h2>
    </div>
    <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
</header>