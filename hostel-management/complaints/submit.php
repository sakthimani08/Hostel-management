<?php
session_start();
include('../config.php');

// Get student email from session
$email = $_SESSION['email'] ?? '';

if (!$email) {
    die("Unauthorized access.");
}

// Get student_id from student table using email
$stmt = $conn->prepare("SELECT student_id FROM student s JOIN user u ON s.user_id = u.user_id WHERE u.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$student_id = $student['student_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $status = 'Pending'; // Default status

    // Get caretaker_id based on category (or set default if you don't map by category)
    // Example: Assign default caretaker_id = 1 (or get it dynamically if needed)
    $caretaker_id = 1;

    $stmt = $conn->prepare("INSERT INTO complaints (student_id, category, description, caretaker_id, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issis", $student_id, $category, $description, $caretaker_id, $status);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=Complaint submitted successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
