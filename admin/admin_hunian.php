<?php
include '../config/config.php';
// Dropdown penghuni aktif
$q_penghuni = mysqli_query($conn, "SELECT id_penghuni, nama FROM tb_penghuni WHERE tanggal_keluar IS NULL");
// Dropdown kamar kosong
$q_kamar = mysqli_query($conn, "SELECT k.id_kamar, k.no_kamar FROM tb_kamar k LEFT JOIN tb_kmr_penghuni kp ON k.id_kamar = kp.id_kamar AND kp.tanggal_keluar IS NULL WHERE kp.id_kmr_penghuni IS NULL");
// Dropdown barang
$q_barang = mysqli_query($conn, "SELECT id_barang, nama_barang FROM tb_barang");

// Assignment hunian
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

// History hunian
$q_history = mysqli_query($conn, "SELECT kp.*, p.nama, k.no_kamar FROM tb_kmr_penghuni kp JOIN tb_penghuni p ON kp.id_penghuni=p.id_penghuni JOIN tb_kamar k ON kp.id_kamar=k.id_kamar ORDER BY kp.id_kmr_penghuni DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hunian Kos</title>
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
        <h1>Admin Hunian Kos</h1>
        <nav>
            <a href="../index.php">Home</a>
            <a href="admin_hunian.php">Hunian</a>
        </nav>
    </div>
    <div class="container">
        <?php if($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <div class="form-box">
            <h2>Assign Penghuni ke Kamar</h2>
            <form method="post" action="../controller/proses.php">
                <label>Penghuni*</label><br>
                <select name="id_penghuni" required>
                    <option value="">- Pilih Penghuni -</option>
                    <?php while($p = mysqli_fetch_assoc($q_penghuni)): ?>
                        <option value="<?= $p['id_penghuni'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label>Kamar*</label><br>
                <select name="id_kamar" required>
                    <option value="">- Pilih Kamar Kosong -</option>
                    <?php while($k = mysqli_fetch_assoc($q_kamar)): ?>
                        <option value="<?= $k['id_kamar'] ?>"><?= htmlspecialchars($k['no_kamar']) ?></option>
                    <?php endwhile; ?>
                </select><br>
                <label>Tanggal Masuk*</label><br>
                <input type="date" name="tanggal_masuk" required><br>
                <h3>Barang Bawaan</h3>
                <div id="barang-list">
                    <div>
                        <select name="barang_id[]">
                            <option value="">- Pilih Barang -</option>
                            <?php mysqli_data_seek($q_barang, 0); while($b = mysqli_fetch_assoc($q_barang)): ?>
                                <option value="<?= $b['id_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?></option>
                            <?php endwhile; ?>
                        </select>
                        <input type="number" name="barang_jumlah[]" min="1" placeholder="Jumlah">
                    </div>
                </div>
                <button type="button" class="btn" onclick="addBarang()">Tambah Barang</button><br><br>
                <button class="btn" type="submit" name="assign_hunian">Assign</button>
            </form>
        </div>
        <h2>History Hunian</h2>
        <table>
            <tr>
                <th>ID</th><th>Penghuni</th><th>Kamar</th><th>Tgl Masuk</th><th>Tgl Keluar</th><th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($q_history)): ?>
            <tr>
                <td><?= $row['id_kmr_penghuni'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['no_kamar']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_keluar']) ?></td>
                <td>
                    <?php if(!$row['tanggal_keluar']): ?>
                        <a class="btn" href="../controller/proses.php?keluar_hunian=<?= $row['id_kmr_penghuni'] ?>" onclick="return confirm('Proses penghuni keluar kos?')">Keluar</a>
                        <a class="btn" href="admin_hunian.php?pindah=<?= $row['id_kmr_penghuni'] ?>">Pindah Kamar</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php if(isset($_GET['pindah'])):
            $id_pindah = $_GET['pindah'];
            $q_pindah = mysqli_query($conn, "SELECT * FROM tb_kmr_penghuni WHERE id_kmr_penghuni='$id_pindah'");
            $data_pindah = mysqli_fetch_assoc($q_pindah);
        ?>
        <div class="form-box">
            <h2>Pindah Kamar</h2>
            <form method="post" action="../controller/proses.php">
                <input type="hidden" name="id_kmr_penghuni" value="<?= $id_pindah ?>">
                <label>Tanggal Keluar*</label><br>
                <input type="date" name="tanggal_keluar" required><br>
                <button class="btn" type="submit" name="pindah_kamar">Proses Pindah</button>
                <a href="admin_hunian.php" class="btn">Batal</a>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <script>
    function addBarang() {
        var barangList = document.getElementById('barang-list');
        var div = document.createElement('div');
        div.innerHTML = barangList.children[0].innerHTML;
        barangList.appendChild(div);
    }
    </script>
</body>
</html>
