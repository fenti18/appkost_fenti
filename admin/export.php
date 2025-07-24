<?php
include '../config/config.php';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="data_penghuni.xls"');
$res = mysqli_query($conn, "SELECT * FROM tb_penghuni");
echo "ID\tNama\tAlamat\tNo HP\tKTP\tTgl Masuk\tTgl Keluar\n";
while($row = mysqli_fetch_assoc($res)) {
    echo $row['id_penghuni']."\t".$row['nama']."\t".$row['alamat']."\t".$row['no_hp']."\t".$row['ktp']."\t".$row['tanggal_masuk']."\t".$row['tanggal_keluar']."\n";
}
?>
