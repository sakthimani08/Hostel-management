<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Add new mess
if (isset($_POST['add'])) {
    $mess_name = $_POST['mess_name'];
    $hostel_id = $_POST['hostel_id'];
    $conn->query("INSERT INTO mess (mess_name, hostel_id) VALUES ('$mess_name', '$hostel_id')");
}

// Delete mess
if (isset($_GET['delete'])) {
    $mess_id = $_GET['delete'];
    $conn->query("DELETE FROM mess WHERE mess_id=$mess_id");
    header("Location: mess.php");
}

// Fetch all messes
$messes = $conn->query("SELECT m.mess_id, m.mess_name, h.name AS hostel_name 
                        FROM mess m 
                        JOIN hostel h ON m.hostel_id = h.hostel_id");

// Fetch hostels for dropdown
$hostels = $conn->query("SELECT hostel_id, name FROM hostel");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Mess</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #aaa; }
        th { background: #2c3e50; color: white; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

    <h2>Manage Mess Facilities</h2>

    <form method="POST">
        <input type="text" name="mess_name" placeholder="Mess Name" required>
        <select name="hostel_id" required>
            <option value="">-- Select Hostel --</option>
            <?php while ($h = $hostels->fetch_assoc()): ?>
                <option value="<?= $h['hostel_id'] ?>"><?= $h['name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="add">Add Mess</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Mess Name</th><th>Hostel</th><th>Action</th>
        </tr>
        <?php while ($row = $messes->fetch_assoc()): ?>
            <tr>
                <td><?= $row['mess_id'] ?></td>
                <td><?= $row['mess_name'] ?></td>
                <td><?= $row['hostel_name'] ?></td>
                <td><a href="?delete=<?= $row['mess_id'] ?>" onclick="return confirm('Delete this mess?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
