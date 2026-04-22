<?php
session_start();
include 'koneksi.php';
include 'fungsi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

if(isset($_POST['simpan'])){
    $no_po = generatePO($conn);
    $tgl = $_POST['tgl'];
    $customer = $_POST['customer'];
    $user = $_SESSION['nama'];

    mysqli_query($conn, "
        INSERT INTO hed_po (no_po, tgl_order, customer, created_by) 
        VALUES ('$no_po','$tgl','$customer','$user')
    ");

    foreach($_POST['ukuran'] as $i => $u){
        if($u != ""){
            // Pembersihan input untuk keamanan dasar
            $jml = $_POST['jml'][$i];
            $harga = $_POST['harga'][$i];
            $kg = $_POST['kg'][$i];

            mysqli_query($conn, "
                INSERT INTO det_po (no_po, ukuran, jml_order, harga, harga_kg) 
                VALUES ('$no_po', '$u', '$jml', '$harga', '$kg')
            ");
        }
    }
    header("location:cetak.php?no_po=$no_po");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input PO - MCP Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <style>
        .table-input input { 
            border: none; 
            background: transparent; 
            width: 100%; 
            padding: 0.5rem;
            outline: none; 
            font-size: 0.95rem;
        }
        .table-input input:focus { 
            background: #eef2f7; 
            border-radius: 4px;
        }
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
            <strong>📝 Form Input PO</strong>
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
        <div class="col-lg-11">
            <div class="card shadow-sm">
                <div class="form-header">
                    <h3 class="mb-1">📋 Input Purchase Order Baru</h3>
                    <p class="mb-0 small">Masukkan detail pesanan dan tekan Simpan untuk melanjutkan ke cetak</p>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" novalidate>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">📅 Tanggal Order</label>
                                <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">👥 Nama Customer</label>
                                <input type="text" name="customer" class="form-control" placeholder="Masukkan nama PT / Perorangan / Toko" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">📦 Detail Item Pesanan</label>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle table-input">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th style="width: 40%;">Ukuran / Produk</th>
                                            <th style="width: 15%;">Jumlah</th>
                                            <th style="width: 20%;">Harga (Rp)</th>
                                            <th style="width: 15%;">Harga/Kg</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=0;$i<10;$i++){ ?>
                                        <tr>
                                            <td><input type="text" name="ukuran[]" placeholder="Masukkan ukuran produk" title="Contoh: 50cm x 40cm x 30cm"></td>
                                            <td><input type="text" name="jml[]" class="text-center" placeholder="0" ></td>
                                            <td><input type="text" name="harga[]" class="text-center" placeholder="0" ></td>
                                            <td><input type="text" name="kg[]" class="text-center" placeholder="0" ></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="dashboard.php" class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" name="simpan" class="btn btn-primary btn-lg">
                                ✅ Simpan & Cetak PO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info mt-3 mb-4" role="alert">
                <strong>💡 Catatan:</strong> Setelah menyimpan, Anda akan diarahkan ke halaman cetak untuk mengunduh atau mencetak PO. Isikan minimal satu item untuk menyimpan pesanan.
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