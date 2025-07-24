<?php
include '../config/config.php';

// Tambah Penghuni
if(isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $ktp = trim($_POST['ktp']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    if($nama == '' || $ktp == '' || $tanggal_masuk == '') {
        header('Location: ../admin/admin_penghuni.php?error=Data wajib diisi'); exit;
    }
    $sql = "INSERT INTO tb_penghuni (nama, alamat, no_hp, ktp, tanggal_masuk) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $nama, $alamat, $no_hp, $ktp, $tanggal_masuk);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_penghuni.php?success=Tambah berhasil');
    } else {
        header('Location: ../admin/admin_penghuni.php?error=Gagal tambah data');
    }
    exit;
}
// CRUD Kamar
if(isset($_POST['tambah_kamar'])) {
    $no_kamar = trim($_POST['no_kamar']);
    $tipe = trim($_POST['tipe']);
    $harga = intval($_POST['harga']);
    if($no_kamar == '' || $harga < 0) {
        header('Location: ../admin/admin_kamar.php?error=Data wajib diisi'); exit;
    }
    $sql = "INSERT INTO tb_kamar (no_kamar, tipe, harga) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $no_kamar, $tipe, $harga);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_kamar.php?success=Tambah berhasil');
    } else {
        header('Location: ../admin/admin_kamar.php?error=Gagal tambah data');
    }
    exit;
}

if(isset($_POST['edit_kamar'])) {
    $id = $_POST['id_kamar'];
    $no_kamar = trim($_POST['no_kamar']);
    $tipe = trim($_POST['tipe']);
    $harga = intval($_POST['harga']);
    if($no_kamar == '' || $harga < 0) {
        header('Location: ../admin/admin_kamar.php?error=Data wajib diisi'); exit;
    }
    $sql = "UPDATE tb_kamar SET no_kamar=?, tipe=?, harga=? WHERE id_kamar=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssii', $no_kamar, $tipe, $harga, $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_kamar.php?success=Edit berhasil');
    } else {
        header('Location: ../admin/admin_kamar.php?error=Gagal edit data');
    }
    exit;
}

if(isset($_GET['hapus_kamar'])) {
    $id = $_GET['hapus_kamar'];
    $sql = "DELETE FROM tb_kamar WHERE id_kamar=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_kamar.php?success=Hapus berhasil');
    } else {
        header('Location: ../admin/admin_kamar.php?error=Gagal hapus data');
    }
    exit;
}

// CRUD Barang
if(isset($_POST['tambah_barang'])) {
    $nama_barang = trim($_POST['nama_barang']);
    $deskripsi = trim($_POST['deskripsi']);
    $biaya_tambahan = intval($_POST['biaya_tambahan']);
    if($nama_barang == '' || $biaya_tambahan < 0) {
        header('Location: ../admin/admin_barang.php?error=Data wajib diisi'); exit;
    }
    $sql = "INSERT INTO tb_barang (nama_barang, deskripsi, biaya_tambahan) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $nama_barang, $deskripsi, $biaya_tambahan);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_barang.php?success=Tambah berhasil');
    } else {
        header('Location: ../admin/admin_barang.php?error=Gagal tambah data');
    }
    exit;
}

if(isset($_POST['edit_barang'])) {
    $id = $_POST['id_barang'];
    $nama_barang = trim($_POST['nama_barang']);
    $deskripsi = trim($_POST['deskripsi']);
    $biaya_tambahan = intval($_POST['biaya_tambahan']);
    if($nama_barang == '' || $biaya_tambahan < 0) {
        header('Location: ../admin/admin_barang.php?error=Data wajib diisi'); exit;
    }
    $sql = "UPDATE tb_barang SET nama_barang=?, deskripsi=?, biaya_tambahan=? WHERE id_barang=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssii', $nama_barang, $deskripsi, $biaya_tambahan, $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_barang.php?success=Edit berhasil');
    } else {
        header('Location: ../admin/admin_barang.php?error=Gagal edit data');
    }
    exit;
}

if(isset($_GET['hapus_barang'])) {
    $id = $_GET['hapus_barang'];
    $sql = "DELETE FROM tb_barang WHERE id_barang=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_barang.php?success=Hapus berhasil');
    } else {
        header('Location: ../admin/admin_barang.php?error=Gagal hapus data');
    }
    exit;
}

// Edit Penghuni
if(isset($_POST['edit'])) {
    $id = $_POST['id_penghuni'];
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $ktp = trim($_POST['ktp']);
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $tanggal_keluar = $_POST['tanggal_keluar'] ? $_POST['tanggal_keluar'] : NULL;
    if($nama == '' || $ktp == '' || $tanggal_masuk == '') {
        header('Location: ../admin/admin_penghuni.php?error=Data wajib diisi'); exit;
    }
    $sql = "UPDATE tb_penghuni SET nama=?, alamat=?, no_hp=?, ktp=?, tanggal_masuk=?, tanggal_keluar=? WHERE id_penghuni=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $nama, $alamat, $no_hp, $ktp, $tanggal_masuk, $tanggal_keluar, $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_penghuni.php?success=Edit berhasil');
    } else {
        header('Location: ../admin/admin_penghuni.php?error=Gagal edit data');
    }
    exit;
}

// Hapus Penghuni
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM tb_penghuni WHERE id_penghuni=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if(mysqli_stmt_execute($stmt)) {
        header('Location: ../admin/admin_penghuni.php?success=Hapus berhasil');
    } else {
        header('Location: ../admin/admin_penghuni.php?error=Gagal hapus data');
    }
    exit;
}
?>
