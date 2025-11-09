\<div class="content">
    <h1>Welcome to Our Booking Portal</h1>
    <p>Reserve vehicles, book halls, and manage quarter bookings with ease.</p>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="dashboard-cards">
        <div class="card">
            <h3>Vehicle Reservation</h3>
            <p>Find and reserve vehicles for your transportation needs.</p>
            <a href="?page=vehicle_booking" class="btn">Book Vehicle</a>
        </div>
        
        <div class="card">
            <h3>Hall Booking</h3>
            <p>Book halls for meetings, events, and gatherings.</p>
            <a href="?page=hall_booking" class="btn">Book Hall</a>
        </div>
        
        <div class="card">
            <h3>Quarters Booking</h3>
            <p>Manage and book your stay in our available quarters.</p>
            <a href="?page=quarters_booking" class="btn">Book Quarters</a>
        </div>
        
        <div class="card">
            <h3>My Bookings</h3>
            <p>View and manage your existing bookings.</p>
            <a href="?page=my_bookings" class="btn">View Bookings</a>
        </div>
    </div>
    
    <?php if (isAdmin()): ?>
    <div style="margin-top: 30px; text-align: center;">
        <a href="?page=admin_dashboard" class="btn">Admin Dashboard</a>
    </div>
    <?php endif; ?>
</div>