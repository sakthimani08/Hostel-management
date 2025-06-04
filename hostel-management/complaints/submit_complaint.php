<?php
// complaints/submit_complaint.php
session_start();
include('../config.php');

$subject = $_POST['subject'] ?? '';
$category = $_POST['category'] ?? '';
$description = $_POST['description'] ?? '';
$email = $_SESSION['email'] ?? 'student1@example.com';

if (!$subject || !$category || !$description) {
    header("Location: raise.php");
    exit;
}

// Get student info
$sql = "SELECT s.student_id, s.hostel_id
        FROM user u
        JOIN student s ON u.user_id = s.student_id
        WHERE u.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();

if (!$student) {
    header("Location: ../student/dashboard.php");
    exit;
}

$student_id = $student['student_id'];
$hostel_id = $student['hostel_id'];

// Get a caretaker for the hostel
$sql2 = "SELECT caretaker_id FROM caretaker WHERE hostel_id = ? LIMIT 1";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $hostel_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$caretaker = $res2->fetch_assoc();

if ($caretaker) {
    $caretaker_id = $caretaker['caretaker_id'];

    // Insert complaint
    $insert = "INSERT INTO complaint (student_id, category, description, caretaker_id, status) 
               VALUES (?, ?, ?, ?, 'Pending')";
    $stmt3 = $conn->prepare($insert);
    $stmt3->bind_param("issi", $student_id, $category, $description, $caretaker_id);
    $stmt3->execute();
}

// Redirect anyway (whether complaint inserted or not)
header("Location: ../student/dashboard.php");
exit;
?>
