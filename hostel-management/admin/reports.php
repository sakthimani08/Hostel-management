<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

// Total complaints
$total = $conn->query("SELECT COUNT(*) AS total FROM complaint")->fetch_assoc()['total'];

// Pending complaints
$pending = $conn->query("SELECT COUNT(*) AS pending FROM complaint WHERE status = 'Pending'")->fetch_assoc()['pending'];

// Resolved complaints
$resolved = $conn->query("SELECT COUNT(*) AS resolved FROM complaint WHERE status = 'Resolved'")->fetch_assoc()['resolved'];

// Complaints by category
$categories_q = $conn->query("SELECT category, COUNT(*) AS count FROM complaint GROUP BY category");
$labels = [];
$counts = [];
while ($row = $categories_q->fetch_assoc()) {
    $labels[] = $row['category'];
    $counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 20px; }
        .summary { display: flex; gap: 30px; margin-bottom: 30px; }
        .card {
            background: #3498db;
            color: white;
            padding: 20px;
            border-radius: 10px;
            width: 180px;
            text-align: center;
        }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>

    <h1>Complaint Summary Report</h1>

    <div class="summary">
        <div class="card">
            <h3>Total</h3>
            <p style="font-size: 24px;"><?= $total ?></p>
        </div>
        <div class="card" style="background:#e67e22;">
            <h3>Pending</h3>
            <p style="font-size: 24px;"><?= $pending ?></p>
        </div>
        <div class="card" style="background:#2ecc71;">
            <h3>Resolved</h3>
            <p style="font-size: 24px;"><?= $resolved ?></p>
        </div>
    </div>

    <h2>Complaints by Category</h2>
    <canvas id="categoryChart" width="600" height="300"></canvas>

    <script>
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'No. of Complaints',
                    data: <?= json_encode($counts) ?>,
                    backgroundColor: '#9b59b6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

</body>
</html>
