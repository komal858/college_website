<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = $_POST['user_type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Student-specific fields
    $fathers_name = isset($_POST['fathers_name']) ? $_POST['fathers_name'] : NULL;
    $roll_no = isset($_POST['roll_no']) ? $_POST['roll_no'] : NULL;
    $year = isset($_POST['year']) ? $_POST['year'] : NULL;
    $branch = isset($_POST['branch']) ? $_POST['branch'] : NULL;
    
    // Faculty-specific fields
    $department = isset($_POST['department']) ? $_POST['department'] : NULL;
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (user_type, name, email, password, fathers_name, roll_no, year, branch, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $user_type, $name, $email, $password, $fathers_name, $roll_no, $year, $branch, $department);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
