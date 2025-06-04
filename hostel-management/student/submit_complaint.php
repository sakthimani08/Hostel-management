<?php
session_start();
include('../config.php');

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}

$email = $_SESSION['email'];
$subject = $_POST['subject'];
$category = $_POST['category'];
$description = $_POST['description'];

// Step 1: Get student_id and hostel_id from email
$stmt = $conn->prepare("SELECT s.student_id, s.hostel_id FROM student s
                        JOIN user u ON u.user_id = s.student_id
                        WHERE u.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Student not found.");
}
$row = $result->fetch_assoc();
$student_id = $row['student_id'];
$hostel_id = $row['hostel_id'];
$stmt->close();

// Step 2: Get caretaker_id based on hostel_id
$stmt2 = $conn->prepare("SELECT caretaker_id FROM caretaker WHERE hostel_id = ? LIMIT 1");
$stmt2->bind_param("i", $hostel_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
if ($result2->num_rows === 0) {
    die("Caretaker not assigned for your hostel.");
}
$row2 = $result2->fetch_assoc();
$caretaker_id = $row2['caretaker_id'];
$stmt2->close();

// Step 3: Insert complaint
$status = "Pending";
$insert = $conn->prepare("INSERT INTO complaint (student_id, category, description, caretaker_id, status)
                          VALUES (?, ?, ?, ?, ?)");
$insert->bind_param("issis", $student_id, $category, $description, $caretaker_id, $status);

if ($insert->execute()) {
    header("Location: ../student/dashboard.php?success=1");
} else {
    echo "Error: " . $insert->error;
}

$insert->close();
$conn->close();
?>
