<?php
session_start(); // WAJIB di baris paling atas

// Cek apakah user sudah login, jika belum lempar kembali ke login.php
if (!isset($_SESSION['login']) || !isset($_SESSION['nama'])) {
    header("location: login.php");
    exit(); // Penting agar kode di bawahnya tidak dieksekusi
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
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark mb-5">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>🏢 MCP Marketing System</strong>
        </a>
        <span class="navbar-text text-white ms-auto">
            <i class="bi bi-person-circle"></i> Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
        </span>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-2">Selamat Datang di Dashboard</h2>
            <p class="text-muted">Kelola Purchase Order Anda dengan mudah</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card" style="border-left-color: #0d6efd;">
            <div style="font-size: 2.5rem; color: #0d6efd; margin-bottom: 1rem;">📝</div>
            <h5>Buat PO Baru</h5>
            <p class="text-muted mb-3">Input pesanan baru ke sistem</p>
            <a href="form_po.php" class="btn btn-primary btn-sm">Buka Form</a>
        </div>

        <div class="dashboard-card" style="border-left-color: #198754;">
            <div style="font-size: 2.5rem; color: #198754; margin-bottom: 1rem;">📋</div>
            <h5>Lihat Daftar PO</h5>
            <p class="text-muted mb-3">Lihat histori semua pesanan</p>
            <a href="list_po.php" class="btn btn-success btn-sm">Lihat Data</a>
        </div>

        <div class="dashboard-card" style="border-left-color: #ffc107;">
            <div style="font-size: 2.5rem; color: #ffc107; margin-bottom: 1rem;">🔐</div>
            <h5>Ubah Password</h5>
            <p class="text-muted mb-3">Perbarui kata sandi akun Anda</p>
            <a href="ganti_password.php" class="btn btn-warning btn-sm text-dark">Ganti Password</a>
        </div>

        <div class="dashboard-card" style="border-left-color: #dc3545;">
            <div style="font-size: 2.5rem; color: #dc3545; margin-bottom: 1rem;">🚪</div>
            <h5>Logout</h5>
            <p class="text-muted mb-3">Keluar dari sistem</p>
            <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin logout?')">Keluar</a>
        </div>
    </div>
</div>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo - Internal Marketing System</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>