<?php
include '../config/config.php';
// Handle pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$sql = "SELECT * FROM tb_kamar WHERE 1";
if($cari != '') {
    $sql .= " AND (no_kamar LIKE '%$cari%' OR tipe LIKE '%$cari%')";
}
$sql .= " ORDER BY id_kamar DESC";
$res = mysqli_query($conn, $sql);

// Untuk edit
$edit = false;
if(isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT * FROM tb_kamar WHERE id_kamar='$id_edit'");
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
    <title>Admin Kamar Kos</title>
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
        <h1>Admin Kamar Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_kamar.php">Kamar</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <input type="text" name="cari" placeholder="Cari nomor/tipe kamar..." value="<?= htmlspecialchars($cari) ?>" style="padding:8px;width:200px;">
            <button class="btn" type="submit">Cari</button>
            <a href="admin_kamar.php" class="btn">Reset</a>
        </form>
        <div class="form-box">
            <h2><?= $edit ? 'Edit Kamar' : 'Tambah Kamar' ?></h2>
            <form method="post" action="../controller/proses.php">
                <?php if($edit): ?><input type="hidden" name="id_kamar" value="<?= $data_edit['id_kamar'] ?>"><?php endif; ?>
                <label>No Kamar*</label><br>
                <input type="text" name="no_kamar" required value="<?= $edit ? htmlspecialchars($data_edit['no_kamar']) : '' ?>"><br>
                <label>Tipe</label><br>
                <input type="text" name="tipe" value="<?= $edit ? htmlspecialchars($data_edit['tipe']) : '' ?>"><br>
                <label>Harga Sewa*</label><br>
                <input type="number" name="harga" required min="0" value="<?= $edit ? htmlspecialchars($data_edit['harga']) : '' ?>"><br><br>
                <button class="btn" type="submit" name="<?= $edit ? 'edit_kamar' : 'tambah_kamar' ?>">Simpan</button>
                <?php if($edit): ?><a href="admin_kamar.php" class="btn">Batal</a><?php endif; ?>
            </form>
        </div>
        <h2>Data Kamar</h2>
        <table>
            <tr>
                <th>ID</th><th>No Kamar</th><th>Tipe</th><th>Harga Sewa</th><th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['id_kamar'] ?></td>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td><?= htmlspecialchars($row['tipe']) ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
                <td>
                    <a class="btn" href="admin_kamar.php?edit=<?= $row['id_kamar'] ?>">Edit</a>
                    <a class="btn danger" href="../controller/proses.php?hapus_kamar=<?= $row['id_kamar'] ?>" onclick="return confirm('Yakin hapus kamar?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
