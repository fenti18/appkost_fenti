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
