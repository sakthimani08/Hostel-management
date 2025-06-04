<?php
include "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $hostel_id = $_POST['hostel_id'];
    $room_id = $_POST['room_id'];
    $mess_id = $_POST['mess_id'];

    // Insert into student table
    $stmt = $conn->prepare("INSERT INTO student (name, department, hostel_id, room_id, mess_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $name, $department, $hostel_id, $room_id, $mess_id);

    if ($stmt->execute()) {
        // Update room availability status
        $conn->query("UPDATE hostel_room SET availability_status = 'Occupied' WHERE room_id = $room_id");

        echo "<script>alert('Student Registered Successfully!'); window.location.href = 'register-student.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register student'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register-student.php");
    exit();
}
?>
