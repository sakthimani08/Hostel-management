<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Caretaker') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$caretaker = $conn->query("SELECT * FROM caretaker c JOIN user u ON u.user_id = c.caretaker_id WHERE u.email='$email'")->fetch_assoc();
$caretaker_id = $caretaker['caretaker_id'];

// Resolve complaint
if (isset($_POST['resolve_id'])) {
    $cid = $_POST['resolve_id'];
    $conn->query("UPDATE complaint SET status='Resolved' WHERE complaint_id='$cid' AND caretaker_id='$caretaker_id'");
}

$complaints = $conn->query("SELECT c.*, s.name AS student_name FROM complaint c
    JOIN student s ON c.student_id = s.student_id
    WHERE c.caretaker_id = '$caretaker_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assigned Complaints</title>
</head>
<body>
    <h2>Assigned Complaints</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($c = $complaints->fetch_assoc()): ?>
        <tr>
            <td><?= $c['complaint_id'] ?></td>
            <td><?= $c['student_name'] ?></td>
            <td><?= $c['category'] ?></td>
            <td><?= $c['description'] ?></td>
            <td><?= $c['status'] ?></td>
            <td>
                <?php if ($c['status'] !== 'Resolved'): ?>
                    <form method="POST">
                        <input type="hidden" name="resolve_id" value="<?= $c['complaint_id'] ?>">
                        <button type="submit">Mark Resolved</button>
                    </form>
                <?php else: ?>
                    ✅ Resolved
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
