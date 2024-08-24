<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

require 'db.php';  // Include your database connection file

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch results
$query = "SELECT subjects.subject_name, results.marks, results.result_date FROM results 
          JOIN subjects ON results.subject_id = subjects.id 
          WHERE results.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student_dashboard.css">
</head>
<body>
    <!-- Logout button -->
    <div class="logout-container">
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <h2>Welcome, <?php echo $user['name']; ?>!</h2>
    
    <h3>Your Details</h3>
    <form method="POST" action="edit_details.php">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
        <label>Father's Name:</label>
        <input type="text" name="fathers_name" value="<?php echo $user['fathers_name']; ?>"><br>
        <label>Roll No:</label>
        <input type="text" name="roll_no" value="<?php echo $user['roll_no']; ?>"><br>
        <label>Year:</label>
        <input type="text" name="year" value="<?php echo $user['year']; ?>"><br>
        <label>Branch:</label>
        <input type="text" name="branch" value="<?php echo $user['branch']; ?>"><br>
        <button type="submit">Update Details</button>
    </form>

    <h3>Your Results</h3>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['subject_name']; ?></td>
                <td><?php echo $row['marks']; ?></td>
                <td><?php echo $row['result_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
