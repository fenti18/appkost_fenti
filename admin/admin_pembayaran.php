<?php
include '../config/config.php';
$id_tagihan = isset($_GET['id_tagihan']) ? intval($_GET['id_tagihan']) : 0;
// Ambil data tagihan
$q_tagihan = mysqli_query($conn, "SELECT t.*, p.nama, k.no_kamar FROM tb_tagihan t JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni=kp.id_kmr_penghuni JOIN tb_penghuni p ON kp.id_penghuni=p.id_penghuni JOIN tb_kamar k ON kp.id_kamar=k.id_kamar WHERE t.id_tagihan='$id_tagihan'");
$tagihan = mysqli_fetch_assoc($q_tagihan);
// Ambil pembayaran
$q_bayar = mysqli_query($conn, "SELECT * FROM tb_bayar WHERE id_tagihan='$id_tagihan' ORDER BY id_bayar ASC");
$total_bayar = 0;
while($b = mysqli_fetch_assoc($q_bayar)) {
    $total_bayar += $b['jumlah'];
    $bayar_list[] = $b;
}
$status = ($total_bayar >= $tagihan['total']) ? 'Lunas' : 'Belum Lunas';
// Update status otomatis
mysqli_query($conn, "UPDATE tb_tagihan SET status='$status' WHERE id_tagihan='$id_tagihan'");
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
// Laporan pembayaran
$q_laporan = mysqli_query($conn, "SELECT b.*, t.bulan, t.tahun, p.nama FROM tb_bayar b JOIN tb_tagihan t ON b.id_tagihan=t.id_tagihan JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni=kp.id_kmr_penghuni JOIN tb_penghuni p ON kp.id_penghuni=p.id_penghuni ORDER BY b.tanggal_bayar DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pembayaran Kos</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        .form-box {background:#e9f5db;padding:20px;border-radius:8px;margin-bottom:30px;}
        .btn {background:#40916c;color:#fff;padding:8px 16px;border:none;border-radius:4px;cursor:pointer;}
        .btn:hover {background:#2d6a4f;}
        .danger {background:#e63946;}
        .danger:hover {background:#b5171e;}
        .msg {padding:10px;margin-bottom:20px;border-radius:4px;}
        .msg.error {background:#ffe5e5;color:#b5171e;}
        .msg.success {background:#e9f5db;color:#2d6a4f;}
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Pembayaran Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_tagihan.php">Tagihan</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if($tagihan): ?>
        <div class="form-box">
            <h2>Pembayaran Tagihan</h2>
            <p><b>Penghuni:</b> <?= htmlspecialchars($tagihan['nama']) ?><br>
            <b>Kamar:</b> <?= htmlspecialchars($tagihan['no_kamar']) ?><br>
            <b>Bulan:</b> <?= htmlspecialchars($tagihan['bulan']) ?>/<?= htmlspecialchars($tagihan['tahun']) ?><br>
            <b>Total Tagihan:</b> Rp <?= number_format($tagihan['total'],0,',','.') ?><br>
            <b>Status:</b> <?= $status ?></p>
            <form method="post" action="../controller/proses.php">
                <input type="hidden" name="id_tagihan" value="<?= $id_tagihan ?>">
                <label>Tanggal Bayar*</label><br>
                <input type="date" name="tanggal_bayar" required value="<?= date('Y-m-d') ?>"><br>
                <label>Jumlah Bayar*</label><br>
                <input type="number" name="jumlah" required min="1" max="<?= $tagihan['total']-$total_bayar ?>" value="<?= $tagihan['total']-$total_bayar ?>"><br>
                <label>Metode</label><br>
                <input type="text" name="metode" value="Transfer"><br><br>
                <button class="btn" type="submit" name="bayar_tagihan">Bayar</button>
            </form>
        </div>
        <h2>Riwayat Pembayaran Tagihan</h2>
        <table>
            <tr>
                <th>ID</th><th>Tanggal Bayar</th><th>Jumlah</th><th>Metode</th>
            </tr>
            <?php if(isset($bayar_list)): foreach($bayar_list as $b): ?>
            <tr>
                <td><?= $b['id_bayar'] ?></td>
                <td><?= htmlspecialchars($b['tanggal_bayar']) ?></td>
                <td>Rp <?= number_format($b['jumlah'],0,',','.') ?></td>
                <td><?= htmlspecialchars($b['metode']) ?></td>
            </tr>
            <?php endforeach; endif; ?>
        </table>
        <?php endif; ?>
        <h2>Laporan Pembayaran</h2>
        <table>
            <tr>
                <th>ID</th><th>Penghuni</th><th>Bulan</th><th>Tahun</th><th>Tanggal Bayar</th><th>Jumlah</th><th>Metode</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($q_laporan)): ?>
            <tr>
                <td><?= $row['id_bayar'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['bulan']) ?></td>
                <td><?= htmlspecialchars($row['tahun']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_bayar']) ?></td>
                <td>Rp <?= number_format($row['jumlah'],0,',','.') ?></td>
                <td><?= htmlspecialchars($row['metode']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
