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
    <link rel="icon" type="image/png" href="assets/img/logo_mcp.png">
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
            padding: 20px;
        }

        .print-container {
            background: white;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-name {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .info-table {
            margin-bottom: 25px;
            font-size: 13px;
            width: 60%;
        }

        .info-table tr {
            height: 24px;
        }

        .info-table td:first-child {
            width: 120px;
            font-weight: 600;
            color: #333;
        }

        .info-table td:nth-child(2) {
            padding-left: 10px;
            color: #555;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #000;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 10px 8px;
            font-size: 13px;
        }

        table.main-table th {
            text-align: center;
            background-color: #e8e8e8;
            font-weight: bold;
            color: #333;
        }

        table.main-table td {
            height: 28px;
        }

        table.main-table td:first-child {
            text-align: center;
            width: 5%;
        }

        table.main-table td:nth-child(2) {
            text-align: left;
            width: auto;
        }

        table.main-table td:nth-child(3) {
            text-align: center;
            width: 15%;
        }

        table.main-table td:nth-child(4) {
            text-align: right;
            width: 20%;
        }

        .signature-container {
            width: 100%;
            margin-top: 40px;
            font-size: 12px;
        }

        .sig-box {
            width: 48%;
            float: left;
            text-align: center;
        }

        .sig-box-right {
            width: 48%;
            float: right;
            text-align: center;
        }

        .sig-box p,
        .sig-box-right p {
            margin-bottom: 60px;
            font-weight: 600;
        }

        .name-tag {
            font-weight: bold;
            color: #333;
            min-height: 20px;
        }

        .clear {
            clear: both;
        }

        .button-container {
            margin-top: 30px;
            text-align: center;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .button-container button,
        .button-container a {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }

        .btn-print:hover {
            background-color: #0a58ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
        }

        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        }

        @page {
            size: A4;
            margin: 20mm 15mm;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                border-radius: 0;
            }

            .button-container {
                display: none !important;
            }

            .no-print {
                display: none !important;
            }

            * {
                box-shadow: none !important;
            }
        }

        @media (max-width: 768px) {
            .print-container {
                padding: 20px;
            }

            .info-table {
                width: 100%;
            }

            .sig-box,
            .sig-box-right {
                width: 100%;
                float: none;
                margin-bottom: 30px;
            }

            .title {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<div class="print-container">
    <div class="company-name">PT MUTIARA CAHAYA PLASTINDO</div>

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
                <th width="20%">Harga/Kg</th>
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
                <td style="text-align: right;"><?= rupiah($row['harga_kg']) ?></td>
            </tr>
            <?php } 
            // Mengisi baris kosong agar total baris selalu 5
            for($i=$no; $i<=5; $i++){
                echo "<tr><td style='text-align: center;'>$i.</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="signature-container">
        <div class="sig-box">
            <p>Diperiksa Oleh :</p>
            <div class="name-tag">( ____________________ )</div> 
        </div>

        <div class="sig-box-right">
            <p>Dibuat Oleh :</p>
            <div class="name-tag">
                ( <?= strtoupper(htmlspecialchars($h['created_by'])); ?> )
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="button-container no-print">
    <button class="btn-print" onclick="window.print()" title="Cetak atau Simpan sebagai PDF">
        🖨️ Cetak PO
    </button>
    <a href="list_po.php" class="btn-back" title="Kembali ke daftar PO">
        ← Kembali ke List PO
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Otomatis membuka dialog print saat halaman dimuat
    window.addEventListener('load', function() {
        // Uncomment baris di bawah jika ingin auto-print saat halaman load
        // window.print();
    });
</script>

</body>
</html>