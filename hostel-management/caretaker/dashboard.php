<?php
session_start();
include "../includes/db.php";

// Check if logged in as caretaker
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Caretaker') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$ct_q = $conn->query("
    SELECT c.caretaker_id 
    FROM caretaker c 
    JOIN user u ON u.user_id = c.caretaker_id 
    WHERE u.email = '$email'
");
$ct = $ct_q->fetch_assoc();
$caretaker_id = $ct['caretaker_id'];

// Mark complaint as resolved
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complaint_id"])) {
    $complaint_id = $_POST["complaint_id"];
    $conn->query("UPDATE complaint SET status='Resolved' WHERE complaint_id='$complaint_id'");
    header("Location: dashboard.php");
    exit();
}

// Get complaints
$complaints = $conn->query("
    SELECT c.complaint_id, c.category, c.description, c.status, s.name AS student_name
    FROM complaint c
    JOIN student s ON c.student_id = s.student_id
    WHERE c.caretaker_id = $caretaker_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Caretaker Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .header {
            background: linear-gradient(90deg, #00c9ff, #92fe9d);
            color: white;
            padding: 30px 40px;
            border-radius: 0 0 12px 12px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .table thead {
            background-color: #0083b0;
            color: white;
        }
        .btn-resolve {
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 6px 15px;
        }
        .btn-resolve:hover {
            background: #219150;
        }
        .status-resolved {
            color: #2ecc71;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h2><i class="fa-solid fa-clipboard-list me-2"></i>Caretaker Complaint Dashboard</h2>
    <p class="mb-0">Manage and resolve student complaints efficiently ✨</p>
</div>

<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Assigned Complaints</h4>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($complaints->num_rows > 0): ?>
                        <?php while ($row = $complaints->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['complaint_id'] ?></td>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td>
                                    <?= $row['status'] === 'Resolved' ? "<span class='status-resolved'>✔ Resolved</span>" : "<span class='text-warning fw-semibold'>Pending</span>" ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] !== 'Resolved'): ?>
                                        <form method="POST">
                                            <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                                            <button type="submit" class="btn btn-resolve"><i class="fa-solid fa-check me-1"></i>Resolve</button>
                                        </form>
                                    <?php else: ?>
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No complaints assigned.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
