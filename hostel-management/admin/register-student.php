<?php
include "../includes/db.php";

// Fetch data for dropdowns
$hostels = $conn->query("SELECT hostel_id, name FROM hostel");
$rooms = $conn->query("SELECT room_id, room_type FROM hostel_room WHERE availability_status = 'Available'");
$messes = $conn->query("SELECT mess_id, mess_name FROM mess");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .form-title {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: #2c3e50;
        }
        .btn-custom {
            width: 100%;
            font-weight: 600;
            background-color: #2ecc71;
            border: none;
        }
        .btn-custom:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-title">🎓 Register New Student</h2>
        <form method="POST" action="process-register-student.php">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" name="department" required>
            </div>

            <div class="mb-3">
                <label for="hostel_id" class="form-label">Hostel</label>
                <select name="hostel_id" class="form-select" required>
                    <option value="">Select Hostel</option>
                    <?php while ($row = $hostels->fetch_assoc()) { ?>
                        <option value="<?= $row['hostel_id'] ?>"><?= $row['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="room_id" class="form-label">Room</label>
                <select name="room_id" class="form-select" required>
                    <option value="">Select Room</option>
                    <?php while ($row = $rooms->fetch_assoc()) { ?>
                        <option value="<?= $row['room_id'] ?>">Room <?= $row['room_id'] ?> - <?= $row['room_type'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="mess_id" class="form-label">Mess</label>
                <select name="mess_id" class="form-select" required>
                    <option value="">Select Mess</option>
                    <?php while ($row = $messes->fetch_assoc()) { ?>
                        <option value="<?= $row['mess_id'] ?>"><?= $row['mess_name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-custom">Register Student</button>
        </form>
    </div>
</body>
</html>
