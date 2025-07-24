<?php
include 'config/config.php';

// Query kamar kosong (belum ada penghuni aktif)
$q_kosong = "SELECT k.no_kamar, k.tipe, k.harga FROM tb_kamar k
LEFT JOIN tb_kmr_penghuni kp ON k.id_kamar = kp.id_kamar AND kp.tanggal_keluar IS NULL
WHERE kp.id_kmr_penghuni IS NULL";
$res_kosong = mysqli_query($conn, $q_kosong);

// Query kamar mendekati jatuh tempo pembayaran (tanggal masuk > 25 hari lalu dan status tagihan belum lunas)
$q_jatuh_tempo = "SELECT k.no_kamar, p.nama, kp.tanggal_masuk, t.bulan, t.tahun, t.total
FROM tb_kmr_penghuni kp
JOIN tb_kamar k ON kp.id_kamar = k.id_kamar
JOIN tb_penghuni p ON kp.id_penghuni = p.id_penghuni
JOIN tb_tagihan t ON kp.id_kmr_penghuni = t.id_kmr_penghuni
WHERE t.status = 'Belum Lunas' AND kp.tanggal_keluar IS NULL AND DATEDIFF(CURDATE(), kp.tanggal_masuk) >= 25";
$res_jatuh_tempo = mysqli_query($conn, $q_jatuh_tempo);

// Query kamar dengan tagihan terlambat (status tagihan belum lunas dan jatuh tempo sudah lewat)
$q_terlambat = "SELECT k.no_kamar, p.nama, t.bulan, t.tahun, t.total
FROM tb_tagihan t
JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni = kp.id_kmr_penghuni
JOIN tb_kamar k ON kp.id_kamar = k.id_kamar
JOIN tb_penghuni p ON kp.id_penghuni = p.id_penghuni
WHERE t.status = 'Belum Lunas' AND kp.tanggal_keluar IS NULL AND t.tahun <= YEAR(CURDATE()) AND t.bulan <= MONTHNAME(CURDATE())";
$res_terlambat = mysqli_query($conn, $q_terlambat);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kos - Publik</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
    <div class="header">
        <h1>Manajemen Kos Fenti</h1>
        <p>Informasi Kamar & Tagihan Real-Time</p>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="admin/">Halaman Admin</a>
    </nav>
    <div class="container">
        <h2>Daftar Kamar Kosong</h2>
        <table>
            <tr><th>No Kamar</th><th>Tipe</th><th>Harga</th></tr>
            <?php while($row = mysqli_fetch_assoc($res_kosong)): ?>
            <tr>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td><?= htmlspecialchars($row['tipe']) ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h2>Kamar Mendekati Jatuh Tempo Pembayaran</h2>
        <table>
            <tr><th>No Kamar</th><th>Nama Penghuni</th><th>Tanggal Masuk</th><th>Bulan Tagihan</th><th>Total</th></tr>
            <?php while($row = mysqli_fetch_assoc($res_jatuh_tempo)): ?>
            <tr>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                <td><?= htmlspecialchars($row['bulan']) ?> <?= htmlspecialchars($row['tahun']) ?></td>
                <td>Rp <?= number_format($row['total'],0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h2>Kamar dengan Tagihan Terlambat</h2>
        <table>
            <tr><th>No Kamar</th><th>Nama Penghuni</th><th>Bulan Tagihan</th><th>Total</th></tr>
            <?php while($row = mysqli_fetch_assoc($res_terlambat)): ?>
            <tr>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['bulan']) ?> <?= htmlspecialchars($row['tahun']) ?></td>
                <td>Rp <?= number_format($row['total'],0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
