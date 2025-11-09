<?php
if (!isAdmin()) {
    header("Location: ?page=login");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $application_id = $_GET['id'];
    $status = $_GET['status'];
    
    $sql = "UPDATE quarters_applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $application_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Quarters application status updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update application status.";
    }
    
    header("Location: ?page=admin_dashboard");
    exit();
} else {
    header("Location: ?page=admin_dashboard");
    exit();
}
?>