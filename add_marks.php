<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'faculty') {
    header("Location: login.php");
    exit();
}

require 'db.php';  // Include your database connection file

// Handle adding marks
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_marks'])) {
    $subject_id = $_POST['subject_id'];
    $student_id = $_POST['student_id'];
    $marks = $_POST['marks'];
    
    $stmt = $conn->prepare("INSERT INTO results (subject_id, student_id, marks, result_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $subject_id, $student_id, $marks);
    if ($stmt->execute()) {
        echo "Marks added successfully!";
    } else {
        echo "Error adding marks!";
    }
}

// Fetch students and subjects for selection
$students_query = "SELECT id, name FROM users WHERE user_type = 'student'";
$students_result = $conn->query($students_query);

$subjects_query = "SELECT id, subject_name FROM subjects WHERE faculty_id = ?";
$stmt = $conn->prepare($subjects_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$subjects_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Marks</title>
    <link rel="stylesheet" href="faculty_dashboard.css">
</head>
<body>
    <div class="add-marks-container">
        <h3>Add Marks</h3>
        <form class="add-marks-form" method="POST" action="">
            <label for="student_id">Select Student:</label>
            <select id="student_id" name="student_id" required>
                <option value="">Select Student</option>
                <?php while ($student = $students_result->fetch_assoc()): ?>
                <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="subject_id">Select Subject:</label>
            <select id="subject_id" name="subject_id" required>
                <option value="">Select Subject</option>
                <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                <option value="<?php echo $subject['id']; ?>"><?php echo $subject['subject_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="marks">Marks:</label>
            <input type="number" id="marks" name="marks" required>
            
            <button type="submit" name="add_marks">Add Marks</button>
        </form>
    </div>
</body>
</html>
