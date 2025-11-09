<?php
if (!isLoggedIn()) {
    header("Location: ?page=login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $hall_id = $_POST['hall_id'];
    $event_name = trim($_POST['event_name']);
    $event_description = trim($_POST['event_description']);
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $participants_count = $_POST['participants_count'];
    
    // Calculate total amount
    $sql = "SELECT internal_price, external_price FROM halls WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hall_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hall = $result->fetch_assoc();
    
    $price = ($_SESSION['user_type'] === 'internal') ? $hall['internal_price'] : $hall['external_price'];
    
    // Calculate hours and total amount
    $start = new DateTime($start_time);
    $end = new DateTime($end_time);
    $interval = $start->diff($end);
    $hours = $interval->h + ($interval->i / 60);
    $total_amount = $hours * $price;
    
    // Insert booking
    $sql = "INSERT INTO hall_bookings (user_id, hall_id, event_name, event_description, booking_date, start_time, end_time, participants_count, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssssid", $user_id, $hall_id, $event_name, $event_description, $booking_date, $start_time, $end_time, $participants_count, $total_amount);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Hall booking submitted successfully! Waiting for admin approval.";
        header("Location: ?page=my_bookings");
        exit();
    } else {
        $_SESSION['error'] = "Failed to submit booking. Please try again.";
        header("Location: ?page=hall_booking");
        exit();
    }
} else {
    header("Location: ?page=hall_booking");
    exit();
}
?>