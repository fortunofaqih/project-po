<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

$pesan = ""; // Variabel untuk menampung notifikasi

if (isset($_POST['ganti'])) {
    $user = $_SESSION['nama'];
    $pass_lama = md5($_POST['lama']);
    $pass_baru = md5($_POST['baru']);

    // Cek apakah password lama benar
    $q = mysqli_query($conn, "SELECT * FROM users WHERE nama='$user' AND password='$pass_lama'");

    if (mysqli_num_rows($q) > 0) {
        // Jika benar, update ke password baru
        $update = mysqli_query($conn, "UPDATE users SET password='$pass_baru' WHERE nama='$user'");
        if ($update) {
            $pesan = "<div class='alert alert-success'>Password berhasil diganti!</div>";
        } else {
            $pesan = "<div class='alert alert-danger'>Terjadi kesalahan sistem.</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>Password lama salah!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - MCP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <style>
        .password-form-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>🔐 Manajemen Akun</strong>
        </a>
        <a href="dashboard.php" class="btn btn-light btn-sm">← Kembali ke Dashboard</a>
    </div>
</nav>

<div class="password-form-container">
    <div class="col-md-5">
        <?= $pesan; ?>

        <div class="card shadow-lg border-0" style="border-left: 4px solid #ffc107;">
            <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white;">
                <h5 class="mb-0 fw-bold">
                    <span style="font-size: 1.25rem;">🔑</span> Ubah Password
                </h5>
                <small>Perbarui password akun Anda untuk keamanan lebih baik</small>
            </div>
            <div class="card-body p-4">
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Saat Ini</label>
                        <input type="password" name="lama" class="form-control" placeholder="Masukkan password lama" required autofocus>
                        <small class="text-muted">Konfirmasi password Anda saat ini</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" name="baru" class="form-control" placeholder="Masukkan password baru" required>
                        <small class="text-muted">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan maksimal</small>
                    </div>
                    <div class="d-flex gap-2 justify-content-between">
                        <a href="dashboard.php" class="btn btn-secondary">
                            ← Kembali
                        </a>
                        <button type="submit" name="ganti" class="btn btn-warning text-dark fw-bold">
                            ✅ Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-white rounded shadow-sm">
            <small class="text-muted d-block">
                <strong>💡 Tips Keamanan:</strong><br>
                • Password minimal 8 karakter<br>
                • Gunakan campuran huruf besar, kecil, angka, dan simbol<br>
                • Jangan gunakan informasi pribadi<br>
                • Ganti password secara berkala
            </small>
        </div>

        <p class="text-center mt-4 text-muted small">
            &copy; 2026 PT Mutiara Cahaya Plastindo
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>