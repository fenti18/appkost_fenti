-- Struktur Tabel
CREATE DATABASE IF NOT EXISTS db_kost;
USE db_kost;

CREATE TABLE tb_penghuni (
    id_penghuni INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat VARCHAR(255),
    no_hp VARCHAR(20),
    tanggal_masuk DATE
);

CREATE TABLE tb_kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    no_kamar VARCHAR(10) NOT NULL,
    tipe VARCHAR(50),
    harga INT
);

CREATE TABLE tb_barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    deskripsi VARCHAR(255)
);

CREATE TABLE tb_kmr_penghuni (
    id_kmr_penghuni INT AUTO_INCREMENT PRIMARY KEY,
    id_penghuni INT,
    id_kamar INT,
    tanggal_masuk DATE,
    tanggal_keluar DATE,
    FOREIGN KEY (id_penghuni) REFERENCES tb_penghuni(id_penghuni) ON DELETE CASCADE,
    FOREIGN KEY (id_kamar) REFERENCES tb_kamar(id_kamar) ON DELETE CASCADE
);

CREATE TABLE tb_brng_bawaan (
    id_brng_bawaan INT AUTO_INCREMENT PRIMARY KEY,
    id_kmr_penghuni INT,
    id_barang INT,
    jumlah INT,
    FOREIGN KEY (id_kmr_penghuni) REFERENCES tb_kmr_penghuni(id_kmr_penghuni) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES tb_barang(id_barang) ON DELETE CASCADE
);

CREATE TABLE tb_tagihan (
    id_tagihan INT AUTO_INCREMENT PRIMARY KEY,
    id_kmr_penghuni INT,
    bulan VARCHAR(20),
    tahun INT,
    total INT,
    status VARCHAR(20),
    FOREIGN KEY (id_kmr_penghuni) REFERENCES tb_kmr_penghuni(id_kmr_penghuni) ON DELETE CASCADE
);

CREATE TABLE tb_bayar (
    id_bayar INT AUTO_INCREMENT PRIMARY KEY,
    id_tagihan INT,
    tanggal_bayar DATE,
    jumlah INT,
    metode VARCHAR(50),
    FOREIGN KEY (id_tagihan) REFERENCES tb_tagihan(id_tagihan) ON DELETE CASCADE
);

-- Sample Data
INSERT INTO tb_penghuni (nama, alamat, no_hp, tanggal_masuk) VALUES
('Andi', 'Jl. Mawar 1', '08123456789', '2025-07-01'),
('Budi', 'Jl. Melati 2', '08129876543', '2025-07-10');

INSERT INTO tb_kamar (no_kamar, tipe, harga) VALUES
('A01', 'Single', 1000000),
('A02', 'Double', 1500000);

INSERT INTO tb_barang (nama_barang, deskripsi) VALUES
('Koper', 'Koper besar warna hitam'),
('Laptop', 'Laptop ASUS 14 inch');

INSERT INTO tb_kmr_penghuni (id_penghuni, id_kamar, tanggal_masuk, tanggal_keluar) VALUES
(1, 1, '2025-07-01', NULL),
(2, 2, '2025-07-10', NULL);

INSERT INTO tb_brng_bawaan (id_kmr_penghuni, id_barang, jumlah) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 2, 1);

INSERT INTO tb_tagihan (id_kmr_penghuni, bulan, tahun, total, status) VALUES
(1, 'Juli', 2025, 1000000, 'Belum Lunas'),
(2, 'Juli', 2025, 1500000, 'Lunas');

INSERT INTO tb_bayar (id_tagihan, tanggal_bayar, jumlah, metode) VALUES
(2, '2025-07-15', 1500000, 'Transfer');
