<?php
if (isLoggedIn()) {
    header("Location: ?page=dashboard");
    exit();
}

$redirect_message = '';
if (isset($_SESSION['redirect_after_login'])) {
    $page = $_SESSION['redirect_after_login'];
    $page_names = [
        'hall_booking' => 'Hall Booking',
        'quarters_booking' => 'Quarters Booking', 
        'vehicle_booking' => 'Vehicle Booking',
        'my_bookings' => 'My Bookings',
        'admin_dashboard' => 'Admin Dashboard'
    ];
    $redirect_message = isset($page_names[$page]) ? 
        "You'll be redirected to " . $page_names[$page] . " after login." : 
        "Please login to continue.";
}
?>
<div class="content auth-container">
    <h1>Login to your account</h1>
    
    <?php if ($redirect_message): ?>
        <div class="alert alert-success"><?php echo $redirect_message; ?></div>
    <?php endif; ?>
    
    <div class="auth-form">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form action="?page=auth" method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" name="login">Login</button>
            
            <div style="margin-top: 15px; text-align: center;">
                <a href="#">Forgot Password?</a>
            </div>
        </form>
        
        <div style="margin-top: 20px; text-align: center;">
            <p>Don't have an account? <a href="?page=register">Register</a></p>
        </div>
    </div>
</div>