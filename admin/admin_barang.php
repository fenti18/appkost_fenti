<?php
include '../config/config.php';
// Handle pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$sql = "SELECT * FROM tb_barang WHERE 1";
if($cari != '') {
    $sql .= " AND (nama_barang LIKE '%$cari%' OR deskripsi LIKE '%$cari%')";
}
$sql .= " ORDER BY id_barang DESC";
$res = mysqli_query($conn, $sql);

// Untuk edit
$edit = false;
if(isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT * FROM tb_barang WHERE id_barang='$id_edit'");
    $data_edit = mysqli_fetch_assoc($q_edit);
}
// Error/success
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Barang Kos</title>
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
        <h1>Admin Barang Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_barang.php">Barang</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <input type="text" name="cari" placeholder="Cari nama/deskripsi barang..." value="<?= htmlspecialchars($cari) ?>" style="padding:8px;width:200px;">
            <button class="btn" type="submit">Cari</button>
            <a href="admin_barang.php" class="btn">Reset</a>
        </form>
        <div class="form-box">
            <h2><?= $edit ? 'Edit Barang' : 'Tambah Barang' ?></h2>
            <form method="post" action="../controller/proses.php">
                <?php if($edit): ?><input type="hidden" name="id_barang" value="<?= $data_edit['id_barang'] ?>"><?php endif; ?>
                <label>Nama Barang*</label><br>
                <input type="text" name="nama_barang" required value="<?= $edit ? htmlspecialchars($data_edit['nama_barang']) : '' ?>"><br>
                <label>Deskripsi</label><br>
                <input type="text" name="deskripsi" value="<?= $edit ? htmlspecialchars($data_edit['deskripsi']) : '' ?>"><br>
                <label>Biaya Tambahan*</label><br>
                <input type="number" name="biaya_tambahan" required min="0" value="<?= $edit && isset($data_edit['biaya_tambahan']) ? htmlspecialchars($data_edit['biaya_tambahan']) : '' ?>"><br><br>
                <button class="btn" type="submit" name="<?= $edit ? 'edit_barang' : 'tambah_barang' ?>">Simpan</button>
                <?php if($edit): ?><a href="admin_barang.php" class="btn">Batal</a><?php endif; ?>
            </form>
        </div>
        <h2>Data Barang</h2>
        <table>
            <tr>
                <th>ID</th><th>Nama Barang</th><th>Deskripsi</th><th>Biaya Tambahan</th><th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['id_barang'] ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td>Rp <?= isset($row['biaya_tambahan']) ? number_format($row['biaya_tambahan'],0,',','.') : '-' ?></td>
                <td>
                    <a class="btn" href="admin_barang.php?edit=<?= $row['id_barang'] ?>">Edit</a>
                    <a class="btn danger" href="../controller/proses.php?hapus_barang=<?= $row['id_barang'] ?>" onclick="return confirm('Yakin hapus barang?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
