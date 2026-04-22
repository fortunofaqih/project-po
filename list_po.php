<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

// Ambil data PO
$q = mysqli_query($conn, "SELECT * FROM hed_po ORDER BY id DESC");
if (!$q) { die("Query Error: " . mysqli_error($conn)); }

// Simpan hasil query ke dalam array agar bisa di-loop dua kali
$data_po = [];
while($row = mysqli_fetch_assoc($q)) {
    $data_po[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List PO - MCP Marketing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">
            <strong>📋 Daftar Purchase Order</strong>
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
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-2">Data Purchase Order</h2>
            <!-- Search & Pagination Controls -->
            <div class="row mb-3 mt-4">
                <div class="col-md-6 mb-2 mb-md-0">
                    <input type="text" id="searchInput" class="form-control" placeholder="🔎 Cari PO, Tanggal, atau Customer...">
                </div>
                <div class="col-md-6 text-md-end">
                    <nav>
                        <ul class="pagination justify-content-md-end mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="poTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="15%">No PO</th>
                            <th width="20%">Tanggal</th>
                            <th>Customer</th>
                            <th width="25%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_po as $d): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($d['no_po']); ?></strong></td>
                            <td><?= date('d/m/Y', strtotime($d['tgl_order'])) ?></td>
                            <td><?= htmlspecialchars($d['customer']); ?></td>
                            <td class="text-center">
                                <a href="cetak.php?no_po=<?= htmlspecialchars($d['no_po']); ?>" target="_blank" class="btn btn-info btn-sm text-white" title="Cetak PO">🖨️ Cetak</a>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $d['id'] ?>" title="Edit PO">✏️ Edit</button>
                                <a href="hapus_po.php?no_po=<?= htmlspecialchars($d['no_po']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus PO ini?')" title="Hapus PO">🗑️ Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach($data_po as $d): 
    $no_po_aktif = $d['no_po'];
?>
<div class="modal fade" id="editModal<?= $d['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $d['id'] ?>" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="editModalLabel<?= $d['id'] ?>">✏️ Edit PO: <?= htmlspecialchars($d['no_po']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="proses_edit_po.php" method="POST">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">📌 Nomor PO</label>
                            <input type="text" name="no_po" class="form-control bg-light" value="<?= htmlspecialchars($d['no_po']); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">📅 Tanggal</label>
                            <input type="date" name="tgl" class="form-control" value="<?= $d['tgl_order'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">👥 Customer</label>
                            <input type="text" name="customer" class="form-control" value="<?= htmlspecialchars($d['customer']); ?>" required>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2">📦 Detail Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th style="width: 40%;">Ukuran / Produk</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 20%;">Harga (Rp)</th>
                                    <th style="width: 15%;">Harga/Kg</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $q_det = mysqli_query($conn, "SELECT * FROM det_po WHERE no_po = '$no_po_aktif'");
                                $count = 0;
                                while($det = mysqli_fetch_assoc($q_det)){ 
                                ?>
                                <tr>
                                    <td>
                                        <input type="text" name="ukuran[]" class="form-control form-control-sm" value="<?= htmlspecialchars($det['ukuran']); ?>">
                                    </td>
                                    <td><input type="text" name="jml[]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($det['jml_order']); ?>" step="0.01"></td>
                                    <td><input type="text" name="harga[]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($det['harga']); ?>" step="0.01"></td>
                                    <td><input type="text" name="kg[]" class="form-control form-control-sm text-center" value="<?= htmlspecialchars($det['harga_kg']); ?>" step="0.01"></td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="hapusBaris(this)" title="Kosongkan baris">×</button>
                                    </td>
                                </tr>
                                <?php $count++; } ?>
                                
                                <?php for($i=$count; $i<8; $i++){ ?>
                                <tr>
                                    <td>
                                        <input type="text" name="ukuran[]" class="form-control form-control-sm" placeholder="Masukkan ukuran produk">
                                    </td>
                                    <td><input type="text" name="jml[]" class="form-control form-control-sm text-center" placeholder="0"></td>
                                    <td><input type="text" name="harga[]" class="form-control form-control-sm text-center" placeholder="Rp 0"></td>
                                    <td><input type="text" name="kg[]" class="form-control form-control-sm text-center" placeholder="Rp 0"></td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="hapusBaris(this)" title="Kosongkan baris">×</button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="update" class="btn btn-warning text-dark fw-bold">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<footer class="mt-5 pt-4 pb-4 border-top text-center text-muted small">
    <p>&copy; 2026 PT Mutiara Cahaya Plastindo</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function hapusBaris(btn) {
    if (confirm('Kosongkan baris ini?')) {
        let row = btn.closest('tr');
        row.querySelectorAll('input').forEach(input => input.value = '');
    }
}
</script>
<script>
// Client-side Search & Pagination
const table = document.getElementById('poTable');
const searchInput = document.getElementById('searchInput');
const pagination = document.getElementById('pagination');
const rows = Array.from(table.querySelectorAll('tbody tr'));
const rowsPerPage = 10;
let currentPage = 1;

function filterRows() {
    const query = searchInput.value.toLowerCase();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
    paginateRows();
}

function paginateRows() {
    const visibleRows = rows.filter(row => row.style.display !== 'none');
    const totalPages = Math.ceil(visibleRows.length / rowsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    visibleRows.forEach((row, idx) => {
        row.style.display = (idx >= (currentPage-1)*rowsPerPage && idx < currentPage*rowsPerPage) ? '' : 'none';
    });
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    let html = '';
    if (totalPages > 1) {
        html += `<li class="page-item${currentPage===1?' disabled':''}"><a class="page-link" href="#" data-page="prev">&laquo;</a></li>`;
        for (let i=1; i<=totalPages; i++) {
            html += `<li class="page-item${i===currentPage?' active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        html += `<li class="page-item${currentPage===totalPages?' disabled':''}"><a class="page-link" href="#" data-page="next">&raquo;</a></li>`;
    }
    pagination.innerHTML = html;
}

pagination.addEventListener('click', function(e) {
    if (e.target.tagName === 'A') {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        const visibleRows = rows.filter(row => row.style.display !== 'none');
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage) || 1;
        if (page === 'prev' && currentPage > 1) currentPage--;
        else if (page === 'next' && currentPage < totalPages) currentPage++;
        else if (!isNaN(page)) currentPage = parseInt(page);
        paginateRows();
    }
});

searchInput.addEventListener('input', function() {
    currentPage = 1;
    filterRows();
});

// Inisialisasi
filterRows();
</script>
</body>
</html>