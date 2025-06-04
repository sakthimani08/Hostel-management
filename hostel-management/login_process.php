<?php
session_start();
include "includes/db.php";

// reCAPTCHA validation - skip for localhost/testing
// You can comment out this block while testing
/*
if (empty($_POST['g-recaptcha-response'])) {
    die("reCAPTCHA failed. Please try again.");
}
*/

// Get form values
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$user_type = $_POST['user_type'];

// Fetch user
$query = $conn->prepare("SELECT * FROM user WHERE email = ? AND phone = ? AND user_type = ?");
$query->bind_param("sss", $email, $phone, $user_type);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // 🔓 Direct password match for testing (no hashing)
    if ($password === $user['password']) {
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];

        if ($user['user_type'] === 'Admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['user_type'] === 'Student') {
            header("Location: student/dashboard.php");
        } elseif ($user['user_type'] === 'Caretaker') {
            header("Location: caretaker/complaints.php");
        }
        exit();
    } else {
        echo "❌ Incorrect password";
    }
} else {
    echo "❌ No user found with those details";
}
?>
