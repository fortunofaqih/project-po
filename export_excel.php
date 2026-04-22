<?php
// export_excel.php
include 'koneksi.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=export_po.xls");

$tgl1 = isset($_GET['tgl1']) ? $_GET['tgl1'] : '';
$tgl2 = isset($_GET['tgl2']) ? $_GET['tgl2'] : '';
$where = '';
if ($tgl1 && $tgl2) {
    $where = "WHERE h.tgl_order BETWEEN '$tgl1' AND '$tgl2'";
}

$sql = "SELECT h.tgl_order, h.customer, d.ukuran, d.jml_order, d.harga, d.harga_kg, h.created_by, h.no_po
        FROM hed_po h
        JOIN det_po d ON h.no_po = d.no_po
        $where
        ORDER BY h.tgl_order DESC, h.no_po DESC";
$res = mysqli_query($conn, $sql);
?>
<table border="1">
    <tr>
        <th>Tgl Order</th>
        <th>Customer</th>
        <th>Ukuran</th>
        <th>Jumlah Order</th>
        <th>Harga (include)</th>
        <th>Harga/Kg</th>
        <th>Marketing</th>
        <th>Nomor PO</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= htmlspecialchars($row['tgl_order']) ?></td>
        <td><?= htmlspecialchars($row['customer']) ?></td>
        <td><?= htmlspecialchars($row['ukuran']) ?></td>
        <td><?= htmlspecialchars($row['jml_order']) ?></td>
        <td><?= htmlspecialchars($row['harga']) ?></td>
        <td><?= htmlspecialchars($row['harga_kg']) ?></td>
        <td><?= htmlspecialchars($row['created_by']) ?></td>
        <td><?= htmlspecialchars($row['no_po']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
