<?php
session_start();
require 'inc/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch type-wise totals
$stmt = $pdo->prepare("
    SELECT type, SUM(amount) AS total 
    FROM investments 
    WHERE user_id = ? 
    GROUP BY type
");
$stmt->execute([$user_id]);
$typeTotals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalValue = array_sum(array_column($typeTotals, 'total'));

$labels = [];
$data = [];
foreach ($typeTotals as $row) {
    $labels[] = $row['type'];
    $data[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investment Analytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📊 Investment Analytics</h2>
        <a href="Editdata.php" class="btn btn-outline-secondary">Add Data</a>
    </div>

    <!-- Summary KPIs -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="text-success">Total Investment</h5>
                    <h3 class="fw-bold">₹ <?= number_format($totalValue, 2) ?></h3>
                </div>
            </div>
        </div>
        <?php foreach ($typeTotals as $item): ?>
            <div class="col-md-4">
                <div class="card border-info shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="text-secondary"><?= htmlspecialchars($item['type']) ?></h6>
                        <h4 class="fw-bold text-info">₹ <?= number_format($item['total'], 2) ?></h4>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($data) > 0): ?>
    <!-- Charts -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Investment Type Distribution</div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Type-wise Value (Bar Chart)</div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No investment data available to display charts.</div>
    <?php endif; ?>
</div>

<!-- Chart Scripts -->
<script>
    const labels = <?= json_encode($labels) ?>;
    const data = <?= json_encode($data) ?>;

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount',
                data: data,
                backgroundColor: ['#198754', '#0d6efd', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => `₹ ${ctx.raw.toLocaleString()}` } }
            }
        }
    });

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount (₹)',
                data: data,
                backgroundColor: '#6610f2'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₹ ' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => `₹ ${ctx.raw.toLocaleString()}` } }
            }
        }
    });
</script>

</body>
</html>
