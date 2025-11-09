<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vavuniya_booking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin';
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Simple routing
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vavuniya DS Office Booking System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f5f5; color: #333; line-height: 1.6; }
        .container { width: 90%; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background-color: #1a5276; color: white; padding: 10px 0; border-bottom: 3px solid #f39c12; }
        .header-top { background-color: #154360; padding: 5px 0; font-size: 0.9rem; }
        .header-top .container { display: flex; justify-content: space-between; align-items: center; }
        .header-top nav a { color: white; text-decoration: none; margin-left: 15px; }
        .header-main { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; }
        .logo { font-size: 1.8rem; font-weight: bold; }
        .search-bar { display: flex; }
        .search-bar input { padding: 8px; border: none; border-radius: 4px 0 0 4px; }
        .search-bar button { background: #f39c12; border: none; padding: 8px 15px; border-radius: 0 4px 4px 0; cursor: pointer; }
        .main-nav { background-color: #2e86c1; padding: 10px 0; }
        .main-nav ul { display: flex; list-style: none; }
        .main-nav li { margin-right: 20px; }
        .main-nav a { color: white; text-decoration: none; font-weight: 500; padding: 5px 10px; border-radius: 4px; transition: background 0.3s; }
        .main-nav a:hover { background-color: #1a5276; }
        .content { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 25px; margin-top: 20px; }
        h1, h2, h3 { color: #1a5276; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #1a5276; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        button, .btn { background-color: #1a5276; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 1rem; transition: background 0.3s; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background-color: #154360; }
        .btn-secondary { background-color: #7f8c8d; }
        .btn-secondary:hover { background-color: #616a6b; }
        .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; text-align: center; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { color: #1a5276; margin-bottom: 10px; }
        .status-pending { color: #f39c12; }
        .status-approved { color: #27ae60; }
        .status-rejected { color: #e74c3c; }
        .auth-container { max-width: 500px; margin: 50px auto; }
        .auth-form { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .footer { background-color: #1a5276; color: white; text-align: center; padding: 20px 0; margin-top: 40px; }
        .footer-links { display: flex; justify-content: center; margin-bottom: 15px; }
        .footer-links a { color: white; margin: 0 15px; text-decoration: none; }
        @media (max-width: 768px) { .header-main { flex-direction: column; text-align: center; } .search-bar { margin: 10px 0; } .main-nav ul { flex-direction: column; } .main-nav li { margin: 5px 0; } .dashboard-cards { grid-template-columns: 1fr; } }
        .hall-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .hall-card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; background: white; }
        .hall-card.booked { background-color: #f8d7da; border-color: #f5c6cb; }
        .hall-card.available { background-color: #d1ecf1; border-color: #bee5eb; }
        .price { font-size: 1.2rem; font-weight: bold; color: #1a5276; margin: 10px 0; }
        .alert { padding: 15px; border-radius: 4px; margin: 20px 0; }
        .alert-success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .alert-error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; }
        .child-entry { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="container">
                <div class="top-links">
                    <a href="#">Disaster Management</a>
                    <a href="#">News</a>
                    <a href="#">FAQ</a>
                    <a href="#">Important Organizations</a>
                    <a href="#">Right to Information</a>
                    <a href="#">Downloads</a>
                    <a href="#">Web Mail</a>
                </div>
                <div class="user-info">
                    <?php if (isLoggedIn()): ?>
                        <span>Welcome, <?php echo $_SESSION['user_name']; ?> | 
                        <a href="?logout=1" style="color:white;">Logout</a></span>
                    <?php else: ?>
                        <a href="?page=login" style="color:white;">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="header-main container">
            <div class="logo">
                Divisional Secretariat - Vavuniya
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
                <button type="button">Q</button>
            </div>
        </div>
        
        <div class="main-nav">
            <div class="container">
                <ul>
                    <li><a href="?page=dashboard">Home</a></li>
                    <li><a href="?page=hall_booking">Hall Booking</a></li>
                    <li><a href="?page=quarters_booking">Quarters Booking</a></li>
                    <li><a href="?page=vehicle_booking">Vehicle Booking</a></li>
                    <li><a href="?page=my_bookings">My Bookings</a></li>
                    <?php if (isAdmin()): ?>
                    <li><a href="?page=admin_dashboard">Admin Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        // Include the requested page
        $page_file = "pages/" . $page . ".php";
        if (file_exists($page_file)) {
            include $page_file;
        } else {
            echo "<div class='content'><h1>Page Not Found</h1><p>The requested page does not exist.</p></div>";
        }
        ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-links">
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Terms of Service</a>
        </div>
        <p>&copy; 2023 Vavuniya Divisional Secretariat Booking System</p>
    </div>
</body>
</html>
<?php $conn->close(); ?>