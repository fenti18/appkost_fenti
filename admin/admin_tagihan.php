<?php
include '../config/config.php';
// Filter periode
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Generate tagihan otomatis awal bulan
if(isset($_GET['generate'])) {
    $q_hunian = mysqli_query($conn, "SELECT kp.id_kmr_penghuni, k.harga FROM tb_kmr_penghuni kp JOIN tb_kamar k ON kp.id_kamar=k.id_kamar WHERE kp.tanggal_keluar IS NULL");
    while($h = mysqli_fetch_assoc($q_hunian)) {
        $id_kmr_penghuni = $h['id_kmr_penghuni'];
        $harga_kamar = $h['harga'];
        // Hitung biaya barang bawaan
        $q_barang = mysqli_query($conn, "SELECT SUM(b.biaya_tambahan * bb.jumlah) AS total_barang FROM tb_brng_bawaan bb JOIN tb_barang b ON bb.id_barang=b.id_barang WHERE bb.id_kmr_penghuni='$id_kmr_penghuni'");
        $d_barang = mysqli_fetch_assoc($q_barang);
        $total_barang = $d_barang['total_barang'] ? $d_barang['total_barang'] : 0;
        $total = $harga_kamar + $total_barang;
        // Cek tagihan sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM tb_tagihan WHERE id_kmr_penghuni='$id_kmr_penghuni' AND bulan='$bulan' AND tahun='$tahun'");
        if(mysqli_num_rows($cek) == 0) {
            mysqli_query($conn, "INSERT INTO tb_tagihan (id_kmr_penghuni, bulan, tahun, total, status) VALUES ('$id_kmr_penghuni', '$bulan', '$tahun', '$total', 'Belum Lunas')");
        }
    }
    header("Location: admin_tagihan.php?success=Tagihan bulan $bulan/$tahun berhasil digenerate"); exit;
}

// Tampilkan tagihan
$sql = "SELECT t.*, p.nama, k.no_kamar FROM tb_tagihan t JOIN tb_kmr_penghuni kp ON t.id_kmr_penghuni=kp.id_kmr_penghuni JOIN tb_penghuni p ON kp.id_penghuni=p.id_penghuni JOIN tb_kamar k ON kp.id_kamar=k.id_kamar WHERE t.bulan='$bulan' AND t.tahun='$tahun' ORDER BY t.id_tagihan DESC";
$res = mysqli_query($conn, $sql);
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tagihan Kos</title>
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
        <h1>Admin Tagihan Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_tagihan.php">Tagihan</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <label>Bulan:</label>
            <select name="bulan">
                <?php for($i=1;$i<=12;$i++): $val = str_pad($i,2,'0',STR_PAD_LEFT); ?>
                <option value="<?= $val ?>" <?= $bulan==$val?'selected':'' ?>><?= $val ?></option>
                <?php endfor; ?>
            </select>
            <label>Tahun:</label>
            <input type="number" name="tahun" value="<?= $tahun ?>" style="width:80px;">
            <button class="btn" type="submit">Filter</button>
            <a href="admin_tagihan.php?generate=1&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn">Generate Tagihan</a>
        </form>
        <h2>Daftar Tagihan Bulan <?= $bulan ?>/<?= $tahun ?></h2>
        <table>
            <tr>
                <th>ID</th><th>Penghuni</th><th>Kamar</th><th>Total</th><th>Status</th><th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['id_tagihan'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td>Rp <?= number_format($row['total'],0,',','.') ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a class="btn" href="../admin/admin_pembayaran.php?id_tagihan=<?= $row['id_tagihan'] ?>">Pembayaran</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
