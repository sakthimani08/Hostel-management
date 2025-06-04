<?php
session_start();
include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $user_type = trim($_POST['usertype']); // form field still sends 'usertype'

    if (empty($email) || empty($phone) || empty($password) || empty($user_type)) {
        echo "<script>alert('All fields are required!'); window.location.href='index.php';</script>";
        exit();
    }

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND phone = ? AND password = ? AND user_type = ?");
    $stmt->bind_param("ssss", $email, $phone, $password, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];

        // Redirect based on user_type
        switch ($user_type) {
            case 'Admin':
                header("Location: admin/dashboard.php");
                break;
            case 'Student':
                header("Location: student/dashboard.php");
                break;
            case 'Caretaker':
                header("Location: caretaker/dashboard.php");
                break;
            default:
                echo "<script>alert('Unknown user type.'); window.location.href='index.php';</script>";
                break;
        }
        exit();
    } else {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='index.php';</script>";
}
?>
