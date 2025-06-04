<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Handle update
if (isset($_POST['allocate'])) {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $mess_id = $_POST['mess_id'];

    $conn->query("UPDATE student SET room_id='$room_id', mess_id='$mess_id' WHERE student_id='$student_id'");
}

// Fetch all students
$students = $conn->query("SELECT * FROM student");

// Fetch all rooms & messes
$rooms = $conn->query("SELECT room_id, hostel_id FROM hostel_room WHERE availability_status = 'Available'");
$messes = $conn->query("SELECT mess_id, mess_name FROM mess");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Allocate Room & Mess</title>
</head>
<body>
    <h2>Room & Mess Allocation</h2>

    <table border="1" cellpadding="8">
        <tr>
            <th>Student Name</th>
            <th>Department</th>
            <th>Current Room</th>
            <th>Current Mess</th>
            <th>Allocate</th>
        </tr>

        <?php while ($s = $students->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= $s['name'] ?></td>
                    <td><?= $s['department'] ?></td>
                    <td><?= $s['room_id'] ?? 'None' ?></td>
                    <td><?= $s['mess_id'] ?? 'None' ?></td>
                    <td>
                        <input type="hidden" name="student_id" value="<?= $s['student_id'] ?>">

                        <select name="room_id" required>
                            <option value="">--Room--</option>
                            <?php
                            $roomList = $conn->query("SELECT room_id, hostel_id FROM hostel_room WHERE availability_status = 'Available'");
                            while ($r = $roomList->fetch_assoc()):
                            ?>
                                <option value="<?= $r['room_id'] ?>"><?= "Room {$r['room_id']} (Hostel {$r['hostel_id']})" ?></option>
                            <?php endwhile; ?>
                        </select>

                        <select name="mess_id" required>
                            <option value="">--Mess--</option>
                            <?php
                            $messList = $conn->query("SELECT mess_id, mess_name FROM mess");
                            while ($m = $messList->fetch_assoc()):
                            ?>
                                <option value="<?= $m['mess_id'] ?>"><?= $m['mess_name'] ?></option>
                            <?php endwhile; ?>
                        </select>

                        <button type="submit" name="allocate">Assign</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
