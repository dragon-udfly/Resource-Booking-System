<?php
if (!isLoggedIn()) {
    header("Location: ?page=login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Generate application ID
    $application_id = 'QTR-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
    // Insert main application
    $sql = "INSERT INTO quarters_applications (
        application_id, user_id, officer_name, nic_number, date_of_birth, designation, 
        service_grade, permanent_address, temporary_address, monthly_salary, 
        duty_assumption_date, telephone_number, is_transferred_officer, transfer_order_details,
        marital_status, spouse_employed, spouse_designation, spouse_department, spouse_salary,
        owns_property_vavuniya, property_details, previous_quarters_stay, previous_stay_duration
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sisssssssdssisssssdiss", 
        $application_id, $user_id, 
        $_POST['officer_name'], $_POST['nic_number'], $_POST['date_of_birth'],
        $_POST['designation'], $_POST['service_grade'], $_POST['permanent_address'],
        $_POST['temporary_address'], $_POST['monthly_salary'], $_POST['duty_assumption_date'],
        $_POST['telephone_number'], $_POST['is_transferred_officer'], $_POST['transfer_order_details'],
        $_POST['marital_status'], $_POST['spouse_employed'], $_POST['spouse_designation'],
        $_POST['spouse_department'], $_POST['spouse_salary'], $_POST['owns_property_vavuniya'],
        $_POST['property_details'], $_POST['previous_quarters_stay'], $_POST['previous_stay_duration']
    );
    
    if ($stmt->execute()) {
        $application_id = $conn->insert_id;
        
        // Insert children details
        if (isset($_POST['child_name'])) {
            $child_stmt = $conn->prepare("INSERT INTO children_details (application_id, child_name, age, grade, school) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($_POST['child_name'] as $index => $child_name) {
                if (!empty($child_name) && !empty($_POST['child_age'][$index])) {
                    $child_stmt->bind_param(
                        "isiss", 
                        $application_id, 
                        $child_name,
                        $_POST['child_age'][$index],
                        $_POST['child_grade'][$index],
                        $_POST['child_school'][$index]
                    );
                    $child_stmt->execute();
                }
            }
            $child_stmt->close();
        }
        
        $_SESSION['success'] = "Quarters application submitted successfully! Your application ID: " . $application_id;
        header("Location: ?page=my_bookings");
        exit();
    } else {
        $_SESSION['error'] = "Failed to submit application. Please try again.";
        header("Location: ?page=quarters_booking");
        exit();
    }
} else {
    header("Location: ?page=quarters_booking");
    exit();
}
?>