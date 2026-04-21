<?php
session_start();
include 'koneksi.php';

if (isset($_GET['no_po'])) {
    $no_po = mysqli_real_escape_string($conn, $_GET['no_po']);

    // Hapus Detail dulu baru Header (urutan database yang baik)
    mysqli_query($conn, "DELETE FROM det_po WHERE no_po = '$no_po'");
    $hapus_h = mysqli_query($conn, "DELETE FROM hed_po WHERE no_po = '$no_po'");

    if ($hapus_h) {
        echo "<script>
                alert('PO $no_po berhasil dihapus!');
                window.location.href='list_po.php';
              </script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}
?>