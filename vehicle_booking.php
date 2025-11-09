<?php if (!isLoggedIn()): ?>
<div class="content">
    <div class="alert alert-error">
        <h3>Authentication Required</h3>
        <p>Please <a href="?page=login" style="color: #1a5276; font-weight: bold;">login</a> to book vehicles.</p>
    </div>
    
    <h2>Vehicle Booking Preview</h2>
    <p>Our vehicle booking system allows you to reserve official vehicles for your transportation needs.</p>
    <ul>
        <li>Official Vehicles for government business</li>
        <li>Pool Vehicles for shared use</li>
        <li>VIP Vehicles for official guests</li>
        <li>Real-time availability checking</li>
    </ul>
    <a href="?page=login" class="btn">Login to Book Vehicles</a>
</div>
<?php else: ?>
<div class="content">
    <h1>Vehicle Reservation</h1>
    <p>Find and reserve vehicles for your transportation needs.</p>
    
    <div class="alert alert-success">
        <h3>Vehicle Booking System</h3>
        <p>This feature is currently under development. Please check back later for vehicle reservation functionality.</p>
    </div>
    
    <div class="dashboard-cards">
        <div class="card">
            <h3>Official Vehicles</h3>
            <p>Book official vehicles for government business</p>
            <button class="btn" disabled>Coming Soon</button>
        </div>
        
        <div class="card">
            <h3>Pool Vehicles</h3>
            <p>Access shared pool vehicles</p>
            <button class="btn" disabled>Coming Soon</button>
        </div>
        
        <div class="card">
            <h3>VIP Vehicles</h3>
            <p>Reserve vehicles for official guests</p>
            <button class="btn" disabled>Coming Soon</button>
        </div>
    </div>
</div>
<?php endif; ?>