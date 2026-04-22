<?php
include 'koneksi.php';
include 'fungsi.php';

$no_po = isset($_GET['no_po']) ? mysqli_real_escape_string($conn, $_GET['no_po']) : '';

if ($no_po == '') {
    die("Nomor PO tidak valid.");
}

$query_h = mysqli_query($conn, "SELECT * FROM hed_po WHERE no_po='$no_po'");
$h = mysqli_fetch_assoc($query_h);
$d = mysqli_query($conn, "SELECT * FROM det_po WHERE no_po='$no_po'");

if (!$h) {
    die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak PO - <?= htmlspecialchars($h['no_po']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 10px;
        }

        /* Ukuran Kontainer untuk Layar */
        .print-container {
            background: white;
            padding: 20px;
            max-width: 210mm; /* Sesuai lebar A5 Landscape */
            margin: 0 auto;
            border: 1px solid #ddd;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .info-table {
            margin-bottom: 15px;
            font-size: 12px;
            width: 50%;
        }

        .info-table td {
            padding: 2px 0;
        }

        .info-table td:first-child {
            width: 100px;
            font-weight: bold;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
        }

        table.main-table th {
            text-align: center;
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
        }

        .signature-container {
            width: 100%;
            margin-top: 20px;
            font-size: 11px;
        }

        .sig-box {
            width: 40%;
            float: left;
            text-align: center;
        }

        .sig-box-right {
            width: 40%;
            float: right;
            text-align: center;
        }

        .sig-box p, .sig-box-right p {
            margin-bottom: 50px;
        }

        .clear { clear: both; }

        /* Pengaturan Cetak A5 */
        @page {
            size: A5 landscape;
            margin: 10mm;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .print-container {
                border: none;
                width: 100%;
                padding: 0;
            }

            .button-container, .no-print {
                display: none !important;
            }
        }

        /* Tombol Navigasi */
        .button-container {
            margin-top: 20px;
            text-align: center;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-custom {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn-print { background: #0d6efd; color: white; }
        .btn-back { background: #6c757d; color: white; }
        .btn-back { 
                background: #dc3545; /* Merah untuk tombol tutup */
                color: white; 
            }

            .btn-back:hover {
                background: #bb2d3b;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
    </style>
</head>
<body>

<div class="print-container">
    <div class="title">PURCHASE ORDER</div>

    <table class="info-table">
        <tr>
            <td>Nomor PO</td>
            <td>: <?= htmlspecialchars($h['no_po']); ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= date('d/m/Y', strtotime($h['tgl_order'])); ?></td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>: <?= htmlspecialchars($h['customer']); ?></td>
        </tr>
    </table>

   <table class="main-table">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>Ukuran / Deskripsi</th>
            <th width="15%">Jml Order</th>
            <th width="25%">Harga</th> <th width="20%">Harga/Kg</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        while($row = mysqli_fetch_assoc($d)){ 
        ?>
        <tr>
            <td style="text-align: center;"><?= $no++ ?>.</td>
            <td><?= htmlspecialchars($row['ukuran']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($row['jml_order']); ?></td>
            <td style="text-align: right;"><?= htmlspecialchars($row['harga']); ?></td> 
            <td style="text-align: right;"><?= htmlspecialchars($row['harga_kg']); ?></td>
        </tr>
        <?php } 
        // Mengisi baris kosong agar estetika A5 tetap terjaga
        for($i=$no; $i<=5; $i++){
            echo "<tr><td style='text-align: center;'>$i.</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
        }
        ?>
    </tbody>
</table>

    <div class="signature-container">
        <div class="sig-box">
            <p>Diperiksa Oleh :</p>
            <div>( ____________________ )</div> 
        </div>

        <div class="sig-box-right">
            <p>Dibuat Oleh :</p>
            <div>
                ( <?= strtoupper(htmlspecialchars($h['created_by'])); ?> )
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="button-container no-print">
    <button class="btn-custom btn-print" onclick="window.print()">
        🖨️ Cetak A5
    </button>
    <button class="btn-custom btn-back" onclick="window.close()">
        ✖ Tutup Halaman
    </button>
</div>

</body>
</html>