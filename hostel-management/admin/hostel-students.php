<?php
include __DIR__ . '/../includes/connection.php';

$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build dynamic SQL
$sql = "
    SELECT 
        s.student_id, 
        s.name AS student_name, 
        s.department, 
        h.name AS hostel_name, 
        s.room_id AS room_number, 
        m.mess_name, 
        f.hostel_fee, 
        f.mess_fee, 
        f.collected_status
    FROM student s
    JOIN hostel h ON s.hostel_id = h.hostel_id
    JOIN mess m ON s.mess_id = m.mess_id
    JOIN fees f ON s.student_id = f.student_id
    WHERE 1
";

if ($filter === 'paid') {
    $sql .= " AND f.collected_status = 'Paid'";
} elseif ($filter === 'unpaid') {
    $sql .= " AND f.collected_status = 'Unpaid'";
}

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (
        s.name LIKE '%$search%' OR 
        s.department LIKE '%$search%' OR 
        h.name LIKE '%$search%'
    )";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hostel Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
        }
        .glass-box {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        h2 {
            font-weight: 700;
            color: #2c3e50;
        }
        .status-paid {
            background-color: #2ecc71;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .status-unpaid {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .filter-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="glass-box">
        <h2><i class="fas fa-users"></i> All Registered Hostel Students</h2>

        <form method="GET" class="filter-form mb-4">
            <select name="filter" class="form-select" style="max-width: 200px;">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All</option>
                <option value="paid" <?= $filter === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="unpaid" <?= $filter === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search by name, dept, hostel" value="<?= htmlspecialchars($search) ?>" style="max-width: 300px;">

            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Hostel</th>
                        <th>Room</th>
                        <th>Mess</th>
                        <th>Hostel Fee</th>
                        <th>Mess Fee</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="text-center">
                            <td><?= $row['student_id'] ?></td>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= $row['department'] ?></td>
                            <td><?= $row['hostel_name'] ?></td>
                            <td><?= $row['room_number'] ?></td>
                            <td><?= $row['mess_name'] ?></td>
                            <td>₹<?= number_format($row['hostel_fee'], 2) ?></td>
                            <td>₹<?= number_format($row['mess_fee'], 2) ?></td>
                            <td>
                                <?php if ($row['collected_status'] === 'Paid'): ?>
                                    <span class="status-paid">Paid</span>
                                <?php else: ?>
                                    <span class="status-unpaid">Unpaid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">No records found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
