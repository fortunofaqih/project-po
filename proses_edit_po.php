<?php
session_start();
include 'koneksi.php';

if (isset($_POST['update'])) {
    // Ambil data dari form
    $no_po    = mysqli_real_escape_string($conn, $_POST['no_po']);
    $tgl      = mysqli_real_escape_string($conn, $_POST['tgl']);
    $customer = mysqli_real_escape_string($conn, $_POST['customer']);

    // 1. Update Header (Hanya tanggal dan customer)
    $q_header = "UPDATE hed_po SET 
                tgl_order = '$tgl', 
                customer = '$customer' 
                WHERE no_po = '$no_po'";
    
    if (!mysqli_query($conn, $q_header)) {
        die("Error Update Header: " . mysqli_error($conn));
    }

    // 2. Hapus detail lama agar bisa diganti dengan yang baru dari form
    // Ini mencegah data tumpang tindih
    $q_delete = "DELETE FROM det_po WHERE no_po = '$no_po'";
    mysqli_query($conn, $q_delete);

    // 3. Simpan detail baru dari array form
    if (isset($_POST['ukuran']) && is_array($_POST['ukuran'])) {
        foreach ($_POST['ukuran'] as $i => $val) {
            
            // Hanya masukkan jika baris ukuran tidak kosong
            if (!empty(trim($val))) {
                $ukuran = mysqli_real_escape_string($conn, $val);
                $jml    = mysqli_real_escape_string($conn, $_POST['jml'][$i]);
                $harga  = mysqli_real_escape_string($conn, $_POST['harga'][$i]);
                $kg     = mysqli_real_escape_string($conn, $_POST['kg'][$i]);

                // Pastikan nama kolom sesuai: no_po, ukuran, jml_order, harga, harga_kg
                $q_det = "INSERT INTO det_po (no_po, ukuran, jml_order, harga, harga_kg) 
                          VALUES ('$no_po', '$ukuran', '$jml', '$harga', '$kg')";
                
                if (!mysqli_query($conn, $q_det)) {
                    die("Error Detail di baris $i: " . mysqli_error($conn));
                }
            }
        }
    }

    echo "<script>
            alert('Data PO $no_po Berhasil Diperbarui!');
            window.location.href='list_po.php';
          </script>";

} else {
    header("location: list_po.php");
}
?>