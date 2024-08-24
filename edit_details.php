<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $fathers_name = $_POST['fathers_name'];
    $roll_no = $_POST['roll_no'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, fathers_name = ?, roll_no = ?, year = ?, branch = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $name, $email, $fathers_name, $roll_no, $year, $branch, $user_id);

    if ($stmt->execute()) {
        echo "Details updated successfully!";
    } else {
        echo "Error updating details!";
    }
}

header("Location: student_dashboard.php");
exit();
?>
