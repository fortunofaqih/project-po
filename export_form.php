<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PO ke Excel - MCP Marketing</title>
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
            <strong>📊 Export Data PO</strong>
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
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="form-header">
                    <h3 class="mb-1">📥 Export PO ke Excel</h3>
                    <p class="mb-0 small">Unduh data Purchase Order berdasarkan rentang tanggal</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="get" action="export_excel.php" class="needs-validation">
                        <div class="mb-3">
                            <label class="form-label fw-bold">📅 Dari Tanggal</label>
                            <input type="date" name="tgl1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">📅 Sampai Tanggal</label>
                            <input type="date" name="tgl2" class="form-control" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                📥 Export ke Excel
                            </button>
                        </div>
                    </form>
                    <div class="alert alert-info mt-3" role="alert">
                        <small><strong>💡 Catatan:</strong> Data akan diekspor dengan format: Tgl Order | Customer | Ukuran | Jumlah | Harga (include) | Harga/Kg | Marketing | Nomor PO</small>
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
