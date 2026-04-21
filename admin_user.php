<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: hanya yang sudah login (dan sebaiknya level admin) yang bisa akses
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

// Proses Simpan User Baru
if (isset($_POST['simpan_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = md5($_POST['password']); // Menggunakan MD5 sesuai data lama Anda

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
    } else {
        $ins = mysqli_query($conn, "INSERT INTO users (username, password, nama) VALUES ('$username', '$password', '$nama')");
        if ($ins) {
            echo "<script>alert('User berhasil didaftarkan!'); window.location.href='admin_user.php';</script>";
        }
    }
}

// Proses Hapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    header("location: admin_user.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - MCP Admin</title>
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark navbar-expand-lg admin-header mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>👨‍💼 Admin Panel - Manajemen User</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <a href="logout.php" class="btn btn-light btn-sm ms-auto" onclick="return confirm('Yakin ingin logout?')">
                🚪 Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row gap-4">
        <!-- FORM TAMBAH USER -->
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-left: 4px solid #0d6efd;">
                <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                    <h5 class="mb-0 fw-bold">
                        <span style="font-size: 1.25rem;">➕</span> Tambah User Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-bold">👤 Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama staff" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">📧 Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username (tanpa spasi)" required>
                            <small class="text-muted">Gunakan username unik untuk login</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">🔐 Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password awal" required>
                            <small class="text-muted">User dapat mengganti password sendiri</small>
                        </div>
                        <button type="submit" name="simpan_user" class="btn btn-primary w-100 fw-bold">
                            ✅ Daftarkan User
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- DAFTAR USER -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 fw-bold">
                        <span style="font-size: 1.25rem;">📋</span> Daftar Pengguna Sistem
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="30%">Nama Lengkap</th>
                                    <th width="30%">Username</th>
                                    <th width="20%">Status</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                                while($u = mysqli_fetch_assoc($q)){
                                ?>
                                <tr>
                                    <td class="fw-bold"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($u['nama']); ?></td>
                                    <td>
                                        <code class="text-primary bg-light p-2 rounded"><?= htmlspecialchars($u['username']); ?></code>
                                    </td>
                                    <td>
                                        <?php if($u['username'] == 'admin'): ?>
                                            <span class="badge bg-danger" title="Administrator sistem">👑 Master Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-success" title="User biasa">👤 User Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($u['username'] != 'admin'): ?>
                                            <a href="?hapus=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user: <?= htmlspecialchars($u['nama']); ?>?')" title="Hapus user ini">
                                                🗑️ Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3" role="alert">
                <strong>💡 Informasi:</strong>
                <ul class="mb-0 mt-2 small">
                    <li>User "admin" adalah akun master dan tidak dapat dihapus</li>
                    <li>Setiap user dapat mengubah password mereka sendiri di halaman dashboard</li>
                    <li>Pastikan setiap user memiliki username yang unik</li>
                    <li>Password akan di-encrypt menggunakan MD5</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo - Admin System</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>