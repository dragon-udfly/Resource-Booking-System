<?php if (!isLoggedIn()): ?>
<div class="content">
    <div class="alert alert-error">
        <h3>Authentication Required</h3>
        <p>Please <a href="?page=login" style="color: #1a5276; font-weight: bold;">login</a> to view your bookings.</p>
    </div>
    
    <h2>My Bookings Preview</h2>
    <p>After logging in, you can view and manage all your bookings and applications in one place.</p>
    <ul>
        <li>View your hall booking status</li>
        <li>Track quarters application progress</li>
        <li>See booking history</li>
        <li>Download application documents</li>
    </ul>
    <a href="?page=login" class="btn">Login to View Bookings</a>
</div>
<?php else: ?>
<div class="content">
    <h1>My Bookings</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <h2>Hall Bookings</h2>
    <?php
    $sql = "SELECT hb.*, h.name as hall_name 
            FROM hall_bookings hb 
            JOIN halls h ON hb.hall_id = h.id 
            WHERE hb.user_id = ? 
            ORDER BY hb.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Hall</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($booking = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $booking['hall_name']; ?></td>
                    <td><?php echo $booking['event_name']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                    <td><?php echo date('g:i A', strtotime($booking['start_time'])) . ' - ' . date('g:i A', strtotime($booking['end_time'])); ?></td>
                    <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                    <td>
                        <span class="status-<?php echo $booking['status']; ?>">
                            ● <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hall bookings found.</p>
    <?php endif; ?>
    
    <h2 style="margin-top: 40px;">Quarters Applications</h2>
    <?php
    $sql = "SELECT * FROM quarters_applications WHERE user_id = ? ORDER BY submitted_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Officer Name</th>
                    <th>Designation</th>
                    <th>Submitted Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($application = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $application['application_id']; ?></td>
                    <td><?php echo $application['officer_name']; ?></td>
                    <td><?php echo $application['designation']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($application['submitted_at'])); ?></td>
                    <td>
                        <span class="status-<?php echo $application['status']; ?>">
                            ● <?php echo ucfirst($application['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No quarters applications found.</p>
    <?php endif; ?>
</div>
<?php endif; ?>