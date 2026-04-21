<?php

function generatePO($conn){
    $tahun = date('Y');

    mysqli_begin_transaction($conn);

    $q = mysqli_query($conn, "
        SELECT no_po FROM hed_po 
        WHERE YEAR(created_at) = '$tahun'
        ORDER BY id DESC 
        LIMIT 1 FOR UPDATE
    ");

    $d = mysqli_fetch_assoc($q);

    if($d){
        $last = explode('/', $d['no_po'])[0];
        $urut = (int)$last + 1;
    } else {
        $urut = 1;
    }

    $no_po = str_pad($urut, 3, "0", STR_PAD_LEFT) . "/PO/" . $tahun;

    mysqli_commit($conn);

    return $no_po;
}

function rupiah($angka){
    return "Rp " . number_format($angka,0,',','.');
}
?>