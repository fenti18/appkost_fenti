<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../config/config.php';
// Total penghuni
$q_penghuni = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_penghuni WHERE tanggal_keluar IS NULL");
$total_penghuni = mysqli_fetch_assoc($q_penghuni)['total'];
// Kamar kosong
$q_kosong = mysqli_query($conn, "SELECT COUNT(*) AS kosong FROM tb_kamar k LEFT JOIN tb_kmr_penghuni kp ON k.id_kamar = kp.id_kamar AND kp.tanggal_keluar IS NULL WHERE kp.id_kmr_penghuni IS NULL");
$kamar_kosong = mysqli_fetch_assoc($q_kosong)['kosong'];
// Tagihan pending
$q_pending = mysqli_query($conn, "SELECT COUNT(*) AS pending FROM tb_tagihan WHERE status='Belum Lunas'");
$tagihan_pending = mysqli_fetch_assoc($q_pending)['pending'];
// Grafik pembayaran bulanan
$grafik = [];
$q_grafik = mysqli_query($conn, "SELECT tahun, bulan, SUM(jumlah) AS total FROM tb_bayar GROUP BY tahun, bulan ORDER BY tahun DESC, bulan DESC LIMIT 12");
while($g = mysqli_fetch_assoc($q_grafik)) {
    $grafik[] = $g;
}
// Notifikasi tagihan telat
$q_telat = mysqli_query($conn, "SELECT t.*, p.nama, k.no_kamar FROM tb_tagihan t JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni=kp.id_kmr_penghuni JOIN tb_penghuni p ON kp.id_penghuni=p.id_penghuni JOIN tb_kamar k ON kp.id_kamar=k.id_kamar WHERE t.status='Belum Lunas' AND t.tahun <= YEAR(CURDATE()) AND t.bulan < MONTH(CURDATE())");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Kos</title>
    <link rel="stylesheet" href="../public/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-box {display:flex;flex-wrap:wrap;gap:20px;margin-bottom:30px;}
        .db-card {background:#e9f5db;padding:20px;border-radius:8px;flex:1;min-width:200px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);}
        .notif {background:#ffe5e5;color:#b5171e;padding:10px;border-radius:4px;margin-bottom:20px;}
        @media (max-width:700px){.dashboard-box{flex-direction:column;}.db-card{min-width:unset;}}
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard Kos</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_penghuni.php">Penghuni</a>
            <a href="admin_kamar.php">Kamar</a>
            <a href="admin_barang.php">Barang</a>
            <a href="admin_hunian.php">Hunian</a>
            <a href="admin_tagihan.php">Tagihan</a>
            <a href="admin_pembayaran.php">Pembayaran</a>
            <a href="backup.php">Backup/Restore</a>
            <a href="export.php">Export Data</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <div class="container">
        <div class="dashboard-box">
            <div class="db-card">
                <h2>Total Penghuni</h2>
                <p><?= $total_penghuni ?></p>
            </div>
            <div class="db-card">
                <h2>Kamar Kosong</h2>
                <p><?= $kamar_kosong ?></p>
            </div>
            <div class="db-card">
                <h2>Tagihan Pending</h2>
                <p><?= $tagihan_pending ?></p>
            </div>
        </div>
        <h2>Grafik Pembayaran Bulanan</h2>
        <canvas id="grafikBayar" height="120"></canvas>
        <script>
        var ctx = document.getElementById('grafikBayar').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= implode(',', array_map(function($g){return "'".$g['bulan'].'/'.$g['tahun']."'";}, $grafik)) ?>],
                datasets: [{
                    label: 'Total Pembayaran',
                    data: [<?= implode(',', array_map(function($g){return $g['total'];}, $grafik)) ?>],
                    backgroundColor: '#40916c'
                }]
            }
        });
        </script>
        <h2>Notifikasi Tagihan Telat</h2>
        <?php while($row = mysqli_fetch_assoc($q_telat)): ?>
            <div class="notif">
                Tagihan <?= htmlspecialchars($row['bulan']) ?>/<?= htmlspecialchars($row['tahun']) ?> untuk penghuni <b><?= htmlspecialchars($row['nama']) ?></b> di kamar <b><?= htmlspecialchars($row['no_kamar']) ?></b> masih <b>Belum Lunas</b>!
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
