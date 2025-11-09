<?php
// Handle login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = ? OR full_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to the requested page or dashboard
            $redirect_page = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard';
            unset($_SESSION['redirect_after_login']);
            
            if ($user['user_type'] === 'admin' && $redirect_page == 'dashboard') {
                header("Location: ?page=admin_dashboard");
            } else {
                header("Location: ?page=" . $redirect_page);
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: ?page=login");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found!";
        header("Location: ?page=login");
        exit();
    }
}

// Handle registration
if (isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $contact_number = trim($_POST['contact_number']);
    
    // Validation
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ?page=register");
        exit();
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long!";
        header("Location: ?page=register");
        exit();
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: ?page=register");
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $sql = "INSERT INTO users (full_name, email, password, user_type, department, contact_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $email, $hashed_password, $user_type, $department, $contact_number);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ?page=login");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed! Please try again.";
        header("Location: ?page=register");
        exit();
    }
}
?>