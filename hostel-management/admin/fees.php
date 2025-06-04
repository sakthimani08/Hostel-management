<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["fee_id"])) {
    $fee_id = $_POST["fee_id"];
    $status = $_POST["status"];
    $conn->query("UPDATE fees SET collected_status='$status' WHERE fee_id='$fee_id'");
    header("Location: fees.php?success=1");
    exit();
}

$query = "SELECT f.fee_id, s.name, s.student_id, f.hostel_fee, f.mess_fee, f.collected_status 
          FROM fees f 
          JOIN student s ON s.student_id = f.student_id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fees Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 60px;
            max-width: 1100px;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .table thead {
            background: #2c3e50;
            color: #fff;
            border-radius: 12px;
        }

        .table tbody tr {
            transition: 0.3s ease-in-out;
        }

        .table tbody tr:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: #f4f8fb;
        }

        .form-select {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 6px 14px;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        h2 {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card-glass">
        <h2 class="text-center mb-4">💸 Fees Status</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success text-center fw-semibold" role="alert">
                ✅ Fee status successfully updated!
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-3">
                <input type="text" id="searchName" placeholder="Search by Name" class="form-control" style="max-width: 250px;">
                <select id="filterStatus" class="form-select" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <button class="btn btn-outline-secondary" id="clearFilters">Clear Filters</button>
        </div>

        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
            <div id="toastFilterClear" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Filters cleared and table reset!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle text-center" id="feesTable">
                <thead class="rounded">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Hostel Fee</th>
                        <th>Mess Fee</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <form method="POST">
                            <td><?= $row['student_id'] ?></td>
                            <td class="student-name"><?= $row['name'] ?></td>
                            <td>₹<?= number_format($row['hostel_fee'], 2) ?></td>
                            <td>₹<?= number_format($row['mess_fee'], 2) ?></td>
                            <td class="status-cell">
                                <select name="status" class="form-select w-auto d-inline">
                                    <option value="Pending" <?= $row['collected_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Paid" <?= $row['collected_status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="fee_id" value="<?= $row['fee_id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const searchName = document.getElementById('searchName');
const filterStatus = document.getElementById('filterStatus');
const clearFilters = document.getElementById('clearFilters');
const tableRows = document.querySelectorAll('#feesTable tbody tr');

function filterTable() {
    const nameVal = searchName.value.toLowerCase();
    const statusVal = filterStatus.value;

    tableRows.forEach(row => {
        const name = row.querySelector('.student-name').textContent.toLowerCase();
        const status = row.querySelector('.status-cell select').value;

        const matchName = name.includes(nameVal);
        const matchStatus = !statusVal || status === statusVal;

        row.style.display = (matchName && matchStatus) ? '' : 'none';
    });
}

searchName.addEventListener('input', filterTable);
filterStatus.addEventListener('change', filterTable);

clearFilters.addEventListener('click', () => {
    searchName.value = '';
    filterStatus.value = '';
    filterTable();

    const toastElement = document.getElementById('toastFilterClear');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
});
</script>
</body>
</html>
