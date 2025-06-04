<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$status_filter = $_GET['status'] ?? '';

// Handle assignment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complaint_id"])) {
    $complaint_id = $_POST["complaint_id"];
    $caretaker_id = $_POST["caretaker_id"];
    $conn->query("UPDATE complaint SET caretaker_id='$caretaker_id', status='In Progress' WHERE complaint_id='$complaint_id'");
    header("Location: complaints.php?success=1");
    exit();
}

// Get complaints with filter
$filterQuery = "";
if ($status_filter && $status_filter != "All") {
    $filterQuery = "WHERE c.status = '$status_filter'";
}

$complaints = $conn->query("
    SELECT c.complaint_id, s.name AS student_name, c.category, c.description, c.status, c.caretaker_id
    FROM complaint c
    JOIN student s ON c.student_id = s.student_id
    $filterQuery
    ORDER BY c.complaint_id DESC
");

// Get caretakers
$caretakers = $conn->query("SELECT caretaker_id, name FROM caretaker");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/images/hostel-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 60px auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }

        h2 {
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            border-radius: 15px;
            overflow: hidden;
        }

        th {
            background-color: #2c3e50 !important;
            color: white !important;
        }

        .btn-update {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .btn-update:hover {
            background-color: #2980b9;
        }

        .badge-resolved {
            background-color: #2ecc71;
        }

        .badge-pending {
            background-color: #e67e22;
        }

        .badge-progress {
            background-color: #f1c40f;
            color: black;
        }

        .filter-section {
            display: flex;
            justify-content: end;
            margin-bottom: 15px;
        }

        .filter-section select {
            max-width: 200px;
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-clipboard-list"></i> Student Complaints</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="toast-container">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ✅ Complaint updated successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="filter-section">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="All" <?= $status_filter == "All" ? "selected" : "" ?>>All</option>
                <option value="Pending" <?= $status_filter == "Pending" ? "selected" : "" ?>>Pending</option>
                <option value="In Progress" <?= $status_filter == "In Progress" ? "selected" : "" ?>>In Progress</option>
                <option value="Resolved" <?= $status_filter == "Resolved" ? "selected" : "" ?>>Resolved</option>
            </select>
            <a href="complaints.php" class="btn btn-outline-secondary">Clear</a>
        </form>
    </div>

    <table class="table table-bordered table-hover align-middle shadow">
        <thead>
        <tr>
            <th>ID</th>
            <th>Student</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Caretaker</th>
            <th>Assign</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $complaints->fetch_assoc()) { ?>
            <tr>
                <form method="POST">
                    <td><?= $row['complaint_id'] ?></td>
                    <td><?= $row['student_name'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td>
                        <?php
                        $status = $row['status'];
                        $badgeClass = match($status) {
                            'Resolved' => 'badge-resolved',
                            'Pending' => 'badge-pending',
                            'In Progress' => 'badge-progress',
                            default => 'bg-secondary'
                        };
                        echo "<span class='badge $badgeClass px-3 py-2'>$status</span>";
                        ?>
                    </td>
                    <td>
                        <select name="caretaker_id" class="form-select" required>
                            <option value="">Assign</option>
                            <?php
                            $caretakers->data_seek(0);
                            while ($c = $caretakers->fetch_assoc()) {
                                $selected = ($c['caretaker_id'] == $row['caretaker_id']) ? "selected" : "";
                                echo "<option value='{$c['caretaker_id']}' $selected>{$c['name']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                        <button type="submit" class="btn btn-update">Update</button>
                    </td>
                </form>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
