<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$msg = "";

if (isset($_POST['change'])) {
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];

    $check = $conn->query("SELECT * FROM user WHERE email='$email' AND password='$old'");
    if ($check->num_rows == 1) {
        $conn->query("UPDATE user SET password='$new' WHERE email='$email'");
        $msg = "Password updated successfully!";
    } else {
        $msg = "Incorrect old password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <p style="color:green"><?= $msg ?></p>

    <form method="POST">
        <input type="password" name="old_pass" placeholder="Old Password" required><br><br>
        <input type="password" name="new_pass" placeholder="New Password" required><br><br>
        <button type="submit" name="change">Change Password</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
