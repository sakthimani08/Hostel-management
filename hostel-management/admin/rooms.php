<?php
session_start();
include "../includes/db.php";

// Check if admin is logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Handle add room
if (isset($_POST['add'])) {
    $hostel_id = $_POST['hostel_id'];
    $room_no = $_POST['room_no'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("INSERT INTO hostel_room (hostel_id, room_no, capacity, available) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $hostel_id, $room_no, $capacity, $capacity);
    $stmt->execute();
    $stmt->close();
}

// Handle delete room
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM hostel_room WHERE room_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: rooms.php");
    exit();
}

// Fetch hostels
$hostels = $conn->query("SELECT * FROM hostel");

// Fetch rooms
$rooms = $conn->query("SELECT r.*, h.name AS hostel_name 
                       FROM hostel_room r 
                       JOIN hostel h ON r.hostel_id = h.hostel_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Management</title>
    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        input, select {
            padding: 6px;
            margin: 5px;
        }

        button {
            padding: 7px 12px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #aaa;
        }

        th {
            background: #2c3e50;
            color: #fff;
        }
    </style>
</head>
<body>

    <h2>Room Management</h2>

    <form method="POST">
        <select name="hostel_id" required>
            <option value="">Select Hostel</option>
            <?php while ($h = $hostels->fetch_assoc()) {
                echo "<option value='{$h['hostel_id']}'>{$h['name']}</option>";
            } ?>
        </select>
        <input type="text" name="room_no" placeholder="Room Number" required>
        <input type="number" name="capacity" placeholder="Capacity" required>
        <button type="submit" name="add">Add Room</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Hostel</th>
            <th>Room No</th>
            <th>Capacity</th>
            <th>Available</th>
            <th>Action</th>
        </tr>
        <?php while ($r = $rooms->fetch_assoc()) { ?>
        <tr>
            <td><?= $r['room_id'] ?></td>
            <td><?= $r['hostel_name'] ?></td>
            <td><?= $r['room_no'] ?></td>
            <td><?= $r['capacity'] ?></td>
            <td><?= $r['available'] ?></td>
            <td><a href="rooms.php?delete=<?= $r['room_id'] ?>" onclick="return confirm('Delete this room?')">Delete</a></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
