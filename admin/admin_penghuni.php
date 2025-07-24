<?php
include '../config/config.php';
// Handle pencarian
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$sql = "SELECT * FROM tb_penghuni WHERE 1";
if($cari != '') {
    $sql .= " AND (nama LIKE '%$cari%' OR ktp LIKE '%$cari%')";
}
$sql .= " ORDER BY id_penghuni DESC";
$res = mysqli_query($conn, $sql);

// Untuk edit
$edit = false;
if(isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT * FROM tb_penghuni WHERE id_penghuni='$id_edit'");
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
    <title>Admin Penghuni Kos</title>
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
        <h1>Admin Penghuni Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_penghuni.php">Penghuni</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="get" style="margin-bottom:20px;">
            <input type="text" name="cari" placeholder="Cari nama/KTP..." value="<?= htmlspecialchars($cari) ?>" style="padding:8px;width:200px;">
            <button class="btn" type="submit">Cari</button>
            <a href="admin_penghuni.php" class="btn">Reset</a>
        </form>
        <div class="form-box">
            <h2><?= $edit ? 'Edit Penghuni' : 'Tambah Penghuni' ?></h2>
            <form method="post" action="../controller/proses.php">
                <?php if($edit): ?><input type="hidden" name="id_penghuni" value="<?= $data_edit['id_penghuni'] ?>"><?php endif; ?>
                <label>Nama*</label><br>
                <input type="text" name="nama" required value="<?= $edit ? htmlspecialchars($data_edit['nama']) : '' ?>"><br>
                <label>Alamat</label><br>
                <input type="text" name="alamat" value="<?= $edit ? htmlspecialchars($data_edit['alamat']) : '' ?>"><br>
                <label>No HP</label><br>
                <input type="text" name="no_hp" value="<?= $edit ? htmlspecialchars($data_edit['no_hp']) : '' ?>"><br>
                <label>No KTP*</label><br>
                <input type="text" name="ktp" required value="<?= $edit ? htmlspecialchars($data_edit['ktp']) : '' ?>"><br>
                <label>Tanggal Masuk*</label><br>
                <input type="date" name="tanggal_masuk" required value="<?= $edit ? htmlspecialchars($data_edit['tanggal_masuk']) : '' ?>"><br>
                <label>Tanggal Keluar</label><br>
                <input type="date" name="tanggal_keluar" value="<?= $edit && $data_edit['tanggal_keluar'] ? htmlspecialchars($data_edit['tanggal_keluar']) : '' ?>"><br><br>
                <button class="btn" type="submit" name="<?= $edit ? 'edit' : 'tambah' ?>">Simpan</button>
                <?php if($edit): ?><a href="admin_penghuni.php" class="btn">Batal</a><?php endif; ?>
            </form>
        </div>
        <h2>Data Penghuni</h2>
        <table>
            <tr>
                <th>ID</th><th>Nama</th><th>Alamat</th><th>No HP</th><th>No KTP</th><th>Tgl Masuk</th><th>Tgl Keluar</th><th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['id_penghuni'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                <td><?= htmlspecialchars($row['ktp']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_keluar']) ?></td>
                <td>
                    <a class="btn" href="admin_penghuni.php?edit=<?= $row['id_penghuni'] ?>">Edit</a>
                    <a class="btn danger" href="../controller/proses.php?hapus=<?= $row['id_penghuni'] ?>" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
