<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}

// Get student ID using email from `user` table
$email = $_SESSION['email'];
$student_q = $conn->query("
    SELECT s.student_id 
    FROM student s 
    JOIN user u ON u.user_id = s.student_id 
    WHERE u.email = '$email'
");

if ($student_q->num_rows === 0) {
    die("Student not found for email: $email");
}

$student = $student_q->fetch_assoc();
$student_id = $student['student_id'];

// Submit complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO complaint (student_id, category, description, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("iss", $student_id, $category, $description);
    $stmt->execute();
}

// Get all complaints of this student
$complaints = $conn->query("
    SELECT c.complaint_id, c.category, c.description, c.status, ct.name AS caretaker_name
    FROM complaint c
    LEFT JOIN caretaker ct ON c.caretaker_id = ct.caretaker_id
    WHERE c.student_id = $student_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #aaa;
        }

        th {
            background: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Raise a Complaint</h2>

    <form method="POST">
        <label>Category:</label>
        <select name="category" required>
            <option value="">Select</option>
            <option value="Room Issue">Room Issue</option>
            <option value="Mess Issue">Mess Issue</option>
            <option value="Electricity">Electricity</option>
            <option value="Water">Water</option>
            <option value="Others">Others</option>
        </select>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <br><br>
        <button type="submit">Submit Complaint</button>
    </form>

    <h2>My Complaints</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Assigned Caretaker</th>
        </tr>
        <?php
        if ($complaints->num_rows > 0) {
            while ($row = $complaints->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['complaint_id']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>" . ($row['caretaker_name'] ?? 'Not Assigned') . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No complaints found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
