<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'faculty') {
    header("Location: login.php");
    exit();
}

require 'db.php';  // Include your database connection file

$faculty_id = $_SESSION['user_id'];

// Handle adding subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
    $subject_name = $_POST['subject_name'];
    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, faculty_id) VALUES (?, ?)");
    $stmt->bind_param("si", $subject_name, $faculty_id);
    if ($stmt->execute()) {
        echo "Subject added successfully!";
    } else {
        echo "Error adding subject!";
    }
}

// Fetch subjects
$query = "SELECT * FROM subjects WHERE faculty_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$subjects = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="faculty_dashboard.css">
</head>
<body>
    <!-- Logout button -->
    <div class="logout-container">
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>

    <h3>Add a Subject</h3>
    <form method="POST" action="">
        <label>Subject Name:</label>
        <input type="text" name="subject_name" required>
        <button type="submit" name="add_subject">Add Subject</button>
    </form>

    <h3>Your Subjects</h3>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $subjects->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['subject_name']; ?></td>
                <td><a href="add_marks.php?subject_id=<?php echo $row['id']; ?>">Add Marks</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
