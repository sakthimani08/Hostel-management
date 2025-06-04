<?php
session_start();
include('../config.php');

$email = $_SESSION['email'] ?? 'student1@example.com'; // fallback

// Get student_id using email
$query = $conn->prepare("
  SELECT s.student_id, s.name, s.department, s.hostel_id, s.room_id, s.mess_id
  FROM student s
  JOIN user u ON u.user_id = s.student_id
  WHERE u.email = ?
");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$student = $result->fetch_assoc();

$student_id = $student['student_id'] ?? 0;
$name = $student['name'] ?? 'Student';
$department = $student['department'] ?? 'Unknown';
$hostel = "Ganga Hostel"; // You can later fetch hostel name via join
$room_type = "Double Sharing"; // Set based on room info if available
$mess = "Veg Mess"; // Same for mess name

// Get complaint counts
$pending = 0;
$resolved = 0;

$count_query = $conn->prepare("SELECT status, COUNT(*) as total FROM complaint WHERE student_id = ? GROUP BY status");
$count_query->bind_param("i", $student_id);
$count_query->execute();
$count_result = $count_query->get_result();

while ($row = $count_result->fetch_assoc()) {
    if (strtolower($row['status']) == 'pending') {
        $pending = $row['total'];
    } elseif (strtolower($row['status']) == 'resolved') {
        $resolved = $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .header {
      background: linear-gradient(90deg, #00b4db, #0083b0);
      color: white;
      padding: 25px 30px;
      border-radius: 0 0 10px 10px;
    }
    .card {
      border: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 15px;
    }
    .summary-card {
      background-color: #f1f4f8;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
    }
    .summary-card h2 {
      font-size: 2rem;
      margin-bottom: 5px;
    }
    .btn-custom {
      background: #00b4db;
      border: none;
      border-radius: 30px;
      padding: 12px 25px;
      color: white;
      font-weight: 500;
    }
  </style>
</head>
<body>

<div class="header">
  <h3>Welcome, <?= htmlspecialchars($name) ?> <span class="wave">👋</span></h3>
  <p class="mb-0">Logged in as: <?= htmlspecialchars($email) ?></p>
</div>

<div class="container mt-4">
  <div class="row">
    <!-- Student Details -->
    <div class="col-md-6">
      <div class="card p-4 mb-4">
        <h5>Your Details</h5>
        <hr>
        <p><strong>Department:</strong> <?= htmlspecialchars($department) ?></p>
        <p><strong>Hostel:</strong> <?= htmlspecialchars($hostel) ?></p>
        <p><strong>Room Type:</strong> <?= htmlspecialchars($room_type) ?></p>
        <p><strong>Mess:</strong> <?= htmlspecialchars($mess) ?></p>
      </div>
    </div>

    <!-- Complaint Summary -->
    <div class="col-md-6">
      <div class="card p-4 mb-4">
        <h5>Complaint Summary</h5>
        <hr>
        <div class="row text-center">
          <div class="col-6">
            <div class="summary-card bg-warning text-dark">
              <i class="fa-solid fa-hourglass-half fa-2x mb-2"></i>
              <h2><?= $pending ?></h2>
              <p class="mb-0">Pending</p>
            </div>
          </div>
          <div class="col-6">
            <div class="summary-card bg-success text-white">
              <i class="fa-solid fa-check-circle fa-2x mb-2"></i>
              <h2><?= $resolved ?></h2>
              <p class="mb-0">Resolved</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Raise Complaint Button -->
    <div class="col-12 text-center">
      <a href="../complaints/raise.php" class="btn btn-custom">Raise a New Complaint</a>
    </div>
  </div>
</div>

</body>
</html>
