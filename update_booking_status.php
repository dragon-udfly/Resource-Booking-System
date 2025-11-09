<?php
if (!isAdmin()) {
    header("Location: ?page=login");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $booking_id = $_GET['id'];
    $status = $_GET['status'];
    
    $sql = "UPDATE hall_bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $booking_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking status updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update booking status.";
    }
    
    header("Location: ?page=admin_dashboard");
    exit();
} else {
    header("Location: ?page=admin_dashboard");
    exit();
}
?>