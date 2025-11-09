<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vavuniya_booking_system";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($dbname);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('internal', 'external', 'admin') DEFAULT 'external',
    department VARCHAR(255),
    contact_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create halls table
$sql = "CREATE TABLE IF NOT EXISTS halls (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    capacity INT(11),
    internal_price DECIMAL(10,2) DEFAULT 0,
    external_price DECIMAL(10,2) DEFAULT 0,
    images TEXT,
    status ENUM('available', 'booked') DEFAULT 'available'
)";

if ($conn->query($sql) === TRUE) {
    echo "Halls table created successfully<br>";
} else {
    echo "Error creating halls table: " . $conn->error . "<br>";
}

// Create hall_bookings table
$sql = "CREATE TABLE IF NOT EXISTS hall_bookings (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    hall_id INT(11) NOT NULL,
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    participants_count INT(11),
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hall_id) REFERENCES halls(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Hall bookings table created successfully<br>";
} else {
    echo "Error creating hall bookings table: " . $conn->error . "<br>";
}

// Create quarters_applications table
$sql = "CREATE TABLE IF NOT EXISTS quarters_applications (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(50) UNIQUE NOT NULL,
    user_id INT(11) NOT NULL,
    officer_name VARCHAR(255) NOT NULL,
    nic_number VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    designation VARCHAR(255) NOT NULL,
    service_grade VARCHAR(100) NOT NULL,
    permanent_address TEXT NOT NULL,
    temporary_address TEXT NOT NULL,
    monthly_salary DECIMAL(10,2) NOT NULL,
    last_salary_increment DATE,
    duty_assumption_date DATE NOT NULL,
    telephone_number VARCHAR(20) NOT NULL,
    is_transferred_officer BOOLEAN DEFAULT FALSE,
    transfer_order_details TEXT,
    
    -- Spouse details
    marital_status ENUM('Married', 'Widowed', 'Divorced', 'Separated'),
    spouse_employed BOOLEAN DEFAULT FALSE,
    spouse_designation VARCHAR(255),
    spouse_department VARCHAR(255),
    spouse_salary DECIMAL(10,2),
    spouse_last_increment DATE,
    
    -- Property ownership
    owns_property_vavuniya BOOLEAN DEFAULT FALSE,
    property_details TEXT,
    
    -- Previous quarters stay
    previous_quarters_stay BOOLEAN DEFAULT FALSE,
    previous_stay_duration VARCHAR(100),
    
    -- Application status
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Quarters applications table created successfully<br>";
} else {
    echo "Error creating quarters applications table: " . $conn->error . "<br>";
}

// Create children_details table
$sql = "CREATE TABLE IF NOT EXISTS children_details (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    application_id INT(11) NOT NULL,
    child_name VARCHAR(255) NOT NULL,
    age INT(11) NOT NULL,
    grade VARCHAR(50),
    school VARCHAR(255),
    FOREIGN KEY (application_id) REFERENCES quarters_applications(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Children details table created successfully<br>";
} else {
    echo "Error creating children details table: " . $conn->error . "<br>";
}

// Insert sample data
// Insert admin user
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (full_name, email, password, user_type, department) 
        VALUES ('System Admin', 'admin@vavuniya.gov.lk', '$admin_password', 'admin', 'Administration')";
$conn->query($sql);

// Insert sample halls
$halls = [
    ['Grand Ballroom', 'Large event space for conferences and gatherings', 200, 0, 100],
    ['Conference Hall 1', 'Medium-sized conference room', 50, 0, 50],
    ['Meeting Room 2', 'Small meeting room for team discussions', 20, 0, 25]
];

foreach ($halls as $hall) {
    $sql = "INSERT IGNORE INTO halls (name, description, capacity, internal_price, external_price) 
            VALUES ('$hall[0]', '$hall[1]', $hall[2], $hall[3], $hall[4])";
    $conn->query($sql);
}

echo "Database setup completed successfully!";

$conn->close();
?>