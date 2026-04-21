<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

// Proses Simpan User Baru
if (isset($_POST['simpan_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = md5($_POST['password']);

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
    // Ambil data dulu untuk proteksi admin
    $data_hapus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id = '$id'"));
    if ($data_hapus['username'] != 'admin') {
        mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    }
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
    <style>
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        .sticky-form {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
        }
        .card { border: none; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark navbar-expand-lg admin-header mb-4 shadow">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <strong>👨‍💼 Admin Panel - MCP Marketing</strong>
        </a>
        <div class="ms-auto">
           
            <a href="logout.php" class="btn btn-light btn-sm text-danger fw-bold" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row g-4"> <div class="col-lg-4">
            <div class="sticky-form">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">➕ Tambah User Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                                <input type="text" name="nama" class="form-control shadow-none" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">USERNAME</label>
                                <input type="text" name="username" class="form-control shadow-none" placeholder="budi_mcp" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">PASSWORD</label>
                                <input type="password" name="password" class="form-control shadow-none" placeholder="******" required>
                            </div>
                            <button type="submit" name="simpan_user" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                ✅ Daftarkan User
                            </button>
                        </form>
                    </div>
                </div>

                <div class="alert alert-warning mt-4 border-0 shadow-sm" role="alert">
                    <h6 class="fw-bold">💡 Perlu Diingat:</h6>
                    <p class="small mb-0">Username tidak boleh sama dan password dienkripsi otomatis dengan MD5.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">📋 Daftar Pengguna Sistem</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Identitas User</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                                while($u = mysqli_fetch_assoc($q)){
                                ?>
                                <tr>
                                    <td class="ps-4 text-muted"><?= $no++ ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($u['nama']); ?></div>
                                        <small class="text-muted">ID: #<?= $u['id']; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border px-2 py-2">@<?= htmlspecialchars($u['username']); ?></span>
                                    </td>
                                    <td>
                                        <?php if($u['username'] == 'admin'): ?>
                                            <span class="badge rounded-pill bg-danger">👑 Master Admin</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-success">👤 Staff Marketing</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($u['username'] != 'admin'): ?>
                                            <a href="?hapus=<?= $u['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus user: <?= $u['nama']; ?>?')" title="Hapus">
                                                🗑️
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Fixed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<footer class="mt-5 py-4 border-top text-center text-muted small bg-white">
    <p class="mb-0">&copy; 2026 PT Mutiara Cahaya Plastindo - Admin Panel System</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>