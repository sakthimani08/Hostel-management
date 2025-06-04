<?php
session_start();
include "../includes/db.php";

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Handle add
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $total_occupancy = $_POST['total_occupancy'];

    $conn->query("INSERT INTO hostel (name, total_occupancy, unoccupied) VALUES ('$name', '$total_occupancy', '$total_occupancy')");
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM hostel WHERE hostel_id = $id");
    header("Location: hostels.php");
    exit();
}

// Get hostel list
$result = $conn->query("SELECT * FROM hostel");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Hostels</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background: #34495e;
            color: white;
        }

        input[type="text"], input[type="number"] {
            padding: 7px;
            width: 95%;
        }

        button {
            padding: 8px 15px;
        }

        form {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <h2>Hostel Management</h2>

    <form method="POST">
        <input type="text" name="name" placeholder="Hostel Name" required>
        <input type="number" name="total_occupancy" placeholder="Total Occupancy" required>
        <button type="submit" name="add">Add Hostel</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Total Occupancy</th>
            <th>Unoccupied</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['hostel_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['total_occupancy'] ?></td>
            <td><?= $row['unoccupied'] ?></td>
            <td><a href="hostels.php?delete=<?= $row['hostel_id'] ?>" onclick="return confirm('Delete this hostel?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
