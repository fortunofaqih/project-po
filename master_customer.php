<?php
// master_customer.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

// Proses INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $sql = "INSERT INTO customer (nama_customer, alamat, telepon) VALUES ('$nama', '$alamat', '$telepon')";
    mysqli_query($conn, $sql);
    header('Location: master_customer.php?success=1');
    exit;
}

// Proses UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $sql = "UPDATE customer SET nama_customer='$nama', alamat='$alamat', telepon='$telepon' WHERE id=$id";
    mysqli_query($conn, $sql);
    header('Location: master_customer.php?success=2');
    exit;
}

// Proses DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM customer WHERE id=$id";
    mysqli_query($conn, $sql);
    header('Location: master_customer.php?success=3');
    exit;
}

// Ambil data customer untuk daftar
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = '';
if ($search) {
    $where = "WHERE nama_customer LIKE '%$search%' OR alamat LIKE '%$search%' OR telepon LIKE '%$search%'";
}
$q = mysqli_query($conn, "SELECT * FROM customer $where ORDER BY nama_customer");
$customers = [];
while ($row = mysqli_fetch_assoc($q)) {
    $customers[] = $row;
}
?>
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
                            <?php
                            if ($_GET['success'] == 1) echo "✅ Data customer berhasil ditambahkan!";
                            elseif ($_GET['success'] == 2) echo "✏️ Data customer berhasil diperbarui!";
                            elseif ($_GET['success'] == 3) echo "🗑️ Data customer berhasil dihapus!";
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="mb-5" id="formTambah">
                        <input type="hidden" name="action" value="add">
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">📋 Daftar Customer</h5>
                        <form method="get" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="🔎 Cari nama, alamat, atau telepon..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Cari</button>
                            <?php if ($search): ?>
                                <a href="master_customer.php" class="btn btn-sm btn-outline-secondary">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="customerTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 30%;">Nama Customer</th>
                                    <th style="width: 40%;">Alamat</th>
                                    <th style="width: 15%;">Telepon</th>
                                    <th style="width: 15%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit">✏️ Edit</button>
                                        <a href="master_customer.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus customer ini?')" title="Hapus">🗑️ Hapus</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Customer -->
<?php foreach ($customers as $row): ?>
<div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="editModalLabel<?= $row['id'] ?>">✏️ Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Customer</label>
                        <input type="text" name="nama_customer" class="form-control" value="<?= htmlspecialchars($row['nama_customer']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($row['telepon']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo - Internal Marketing System</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Pagination
const table = document.getElementById('customerTable');
const pagination = document.getElementById('pagination');
const rows = Array.from(table.querySelectorAll('tbody tr'));
const rowsPerPage = 5;
let currentPage = 1;

function displayRows() {
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    rows.forEach((row, idx) => {
        row.style.display = (idx >= start && idx < end) ? '' : 'none';
    });
}

function createPagination() {
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    let html = '';
    
    if (totalPages > 1) {
        html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">← Sebelumnya</a>
                </li>`;
        
        for (let i = 1; i <= totalPages; i++) {
            html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
        }
        
        html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Selanjutnya →</a>
                </li>`;
    }
    
    pagination.innerHTML = html;
}

function changePage(page) {
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        displayRows();
        createPagination();
    }
}

// Inisialisasi
displayRows();
createPagination();
</script>
</body>
</html>
