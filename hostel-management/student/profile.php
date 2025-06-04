<?php
session_start();
include "../includes/db.php";

// Check if student is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$student_q = $conn->query("SELECT * FROM student WHERE email = '$email'");
$student = $student_q->fetch_assoc();
$success = $error = "";

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE student SET name=?, phone=? WHERE email=?");
    $stmt->bind_param("sss", $name, $phone, $email);
    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $student['name'] = $name;
        $student['phone'] = $phone;
    } else {
        $error = "Error updating profile.";
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Get existing password
    $user_q = $conn->query("SELECT password FROM user WHERE email = '$email'");
    $user = $user_q->fetch_assoc();

    if ($current === $user['password']) {
        if ($new === $confirm) {
            $conn->query("UPDATE user SET password='$new' WHERE email='$email'");
            $success = "Password changed successfully!";
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        input { padding: 6px; margin: 5px 0; width: 100%; }
        button { padding: 8px 15px; margin-top: 10px; }
        .box { border: 1px solid #aaa; padding: 20px; margin-bottom: 30px; border-radius: 6px; max-width: 400px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

    <h2>My Profile</h2>

    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <div class="box">
        <h3>Update Profile Info</h3>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $student['name'] ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= $student['phone'] ?>" required>

            <button type="submit" name="update_profile">Update</button>
        </form>
    </div>

    <div class="box">
        <h3>Change Password</h3>
        <form method="POST">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>

</body>
</html>
