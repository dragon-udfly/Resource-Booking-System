<?php if (!isAdmin()): ?>
<div class="content">
    <div class="alert alert-error">
        <h3>Access Denied</h3>
        <p>You don't have permission to access the admin dashboard.</p>
        <a href="?page=dashboard" class="btn">Go to Dashboard</a>
    </div>
</div>
<?php else: ?>
<div class="content">
    <h1>Admin Dashboard</h1>
    
    <h2>Pending Hall Booking Requests</h2>
    <?php
    $sql = "SELECT hb.*, u.full_name, u.email, u.department, h.name as hall_name 
            FROM hall_bookings hb 
            JOIN users u ON hb.user_id = u.id 
            JOIN halls h ON hb.hall_id = h.id 
            WHERE hb.status = 'pending' 
            ORDER BY hb.created_at DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Hall</th>
                    <th>Event</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($booking = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <strong><?php echo $booking['full_name']; ?></strong><br>
                        <small><?php echo $booking['department']; ?></small><br>
                        <small><?php echo $booking['email']; ?></small>
                    </td>
                    <td><?php echo $booking['hall_name']; ?></td>
                    <td><?php echo $booking['event_name']; ?></td>
                    <td>
                        <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?><br>
                        <?php echo date('g:i A', strtotime($booking['start_time'])) . ' - ' . date('g:i A', strtotime($booking['end_time'])); ?>
                    </td>
                    <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                    <td>
                        <a href="?page=update_booking_status&id=<?php echo $booking['id']; ?>&status=approved" class="btn" style="padding: 5px 10px; font-size: 0.9rem;">Approve</a>
                        <a href="?page=update_booking_status&id=<?php echo $booking['id']; ?>&status=rejected" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending hall booking requests.</p>
    <?php endif; ?>
    
    <h2 style="margin-top: 40px;">Pending Quarters Applications</h2>
    <?php
    $sql = "SELECT qa.*, u.full_name, u.email 
            FROM quarters_applications qa 
            JOIN users u ON qa.user_id = u.id 
            WHERE qa.status = 'pending' 
            ORDER BY qa.submitted_at DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Officer Name</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Submitted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($application = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $application['application_id']; ?></td>
                    <td><?php echo $application['officer_name']; ?></td>
                    <td><?php echo $application['designation']; ?></td>
                    <td><?php echo $application['user_department'] ?? 'N/A'; ?></td>
                    <td><?php echo date('M j, Y', strtotime($application['submitted_at'])); ?></td>
                    <td>
                        <a href="?page=update_quarters_status&id=<?php echo $application['id']; ?>&status=approved" class="btn" style="padding: 5px 10px; font-size: 0.9rem;">Approve</a>
                        <a href="?page=update_quarters_status&id=<?php echo $application['id']; ?>&status=rejected" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9rem;">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending quarters applications.</p>
    <?php endif; ?>
</div>
<?php endif; ?>