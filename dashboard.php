<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi disertakan

if (!isset($_SESSION['login']) || !isset($_SESSION['nama'])) {
    header("location: login.php");
    exit();
}

// 1. QUERY DATA UNTUK GRAFIK CUSTOMER TERBANYAK (Top 5)
$query_customer = mysqli_query($conn, "SELECT customer, COUNT(*) as total FROM hed_po GROUP BY customer ORDER BY total DESC LIMIT 5");
$labels_cust = [];
$data_cust = [];
while($row = mysqli_fetch_assoc($query_customer)) {
    $labels_cust[] = $row['customer'];
    $data_cust[] = $row['total'];
}

// 2. QUERY DATA UNTUK TREN PO BULANAN (6 Bulan Terakhir)
$query_bulanan = mysqli_query($conn, "SELECT DATE_FORMAT(tgl_order, '%M') as bulan, COUNT(*) as total 
                                      FROM hed_po 
                                      WHERE tgl_order >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                                      GROUP BY MONTH(tgl_order) 
                                      ORDER BY tgl_order ASC");
$labels_mth = [];
$data_mth = [];
while($row = mysqli_fetch_assoc($query_bulanan)) {
    $labels_mth[] = $row['bulan'];
    $data_mth[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MCP Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background-color: #343a40;">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>🏢 MCP Marketing System</strong>
        </a>
        <span class="navbar-text text-white ms-auto">
            Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
        </span>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-2">Selamat Datang di Dashboard</h2>
            <p class="text-muted">Analisa data Purchase Order secara real-time</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-primary border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">📝</div>
                    <h5>Buat PO Baru</h5>
                    <a href="form_po.php" class="btn btn-primary btn-sm mt-2">Buka Form</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-success border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">📋</div>
                    <h5>Daftar PO</h5>
                    <a href="list_po.php" class="btn btn-success btn-sm mt-2">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-info border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">📦</div>
                    <h5>Export Excel</h5>
                    <a href="export_form.php" class="btn btn-info btn-sm mt-2 text-white">Export</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-secondary border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">👥</div>
                    <h5>Master Customer</h5>
                    <a href="master_customer.php" class="btn btn-secondary btn-sm mt-2">Kelola</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-warning border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">🔐</div>
                    <h5>Password</h5>
                    <a href="ganti_password.php" class="btn btn-warning btn-sm mt-2">Ganti</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card h-100 shadow-sm border-0 border-start border-danger border-4">
                <div class="card-body text-center">
                    <div class="display-5 mb-2">🚪</div>
                    <h5>Logout</h5>
                    <a href="logout.php" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Logout?')">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Top 5 Customer</h5>
                    <canvas id="chartCustomer"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Tren PO (6 Bulan Terakhir)</h5>
                    <canvas id="chartBulanan"></canvas>
                </div>
            </div>
        </div>
    </div>

    
</div>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo</p>
</footer>

<script>
// Konfigurasi Grafik Customer (Doughnut Chart)
const ctxCust = document.getElementById('chartCustomer').getContext('2d');
new Chart(ctxCust, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labels_cust); ?>,
        datasets: [{
            label: 'Jumlah PO',
            data: <?= json_encode($data_cust); ?>,
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6610f2'],
        }]
    },
    options: { responsive: true }
});

// Konfigurasi Grafik Tren Bulanan (Line Chart)
const ctxMth = document.getElementById('chartBulanan').getContext('2d');
new Chart(ctxMth, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels_mth); ?>,
        datasets: [{
            label: 'Total PO Dibuat',
            data: <?= json_encode($data_mth); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>