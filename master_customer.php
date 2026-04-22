<?php
// master_customer.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_customer'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $sql = "INSERT INTO customer (nama_customer, alamat, telepon) VALUES ('$nama', '$alamat', '$telepon')";
    mysqli_query($conn, $sql);
    header('Location: master_customer.php?success=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Customer - MCP Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <style>
        .form-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 2rem 1.5rem;
            border-radius: 8px 8px 0 0;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>👥 Master Customer</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <a href="dashboard.php" class="btn btn-light btn-sm ms-auto">← Kembali ke Dashboard</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="form-header">
                    <h3 class="mb-1">📦 Input Master Customer</h3>
                    <p class="mb-0 small">Kelola daftar customer untuk Purchase Order</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✅ Data customer berhasil ditambahkan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="mb-5">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Customer</label>
                                <input type="text" name="nama_customer" class="form-control" placeholder="Masukkan nama customer" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telepon</label>
                                <input type="text" name="telepon" class="form-control" placeholder="Nomor telepon">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                ✅ Simpan Customer
                            </button>
                            <a href="dashboard.php" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                    
                    <hr>
                    <h5 class="fw-bold mb-3">📋 Daftar Customer</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama Customer</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama_customer");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($row['nama_customer'])."</td>";
                                    echo "<td>".htmlspecialchars($row['alamat'])."</td>";
                                    echo "<td>".htmlspecialchars($row['telepon'])."</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo - Internal Marketing System</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
