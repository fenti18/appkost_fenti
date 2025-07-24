
# Aplikasi Manajemen Kos Fenti

## Fitur Utama
- Dashboard admin: ringkasan data, grafik pembayaran, notifikasi tagihan telat
- Sistem login admin
- CRUD penghuni, kamar, barang, hunian, tagihan, pembayaran
- Auto-generate tagihan bulanan, sistem cicilan, status lunas otomatis
- Export data Excel, backup/restore database
- Responsive design, error handling komprehensif

## Struktur Folder
```
appkost_fenti/
├── config/         # File konfigurasi
├── controller/     # Logic aplikasi
├── model/          # Model database
├── public/         # Asset publik (css/js)
├── sql/            # File SQL setup/backup
├── admin/          # Halaman admin
├── view/           # Tampilan web
└── README.md       # Dokumentasi
```

## Cara Deploy
1. Import `sql/db_kost_setup.sql` ke MySQL
2. Edit `config/config.php` sesuai setting server
3. Jalankan aplikasi di server web (XAMPP/Laragon)
4. Login admin: user `admin`, password `admin123`
5. Gunakan menu admin untuk kelola data, backup, export, dll

## Error Handling
- Semua proses CRUD, pembayaran, dan backup/restore dilengkapi validasi dan notifikasi error/success

## Export & Backup
- Export data Excel: menu Export Data
- Backup/Restore: menu Backup/Restore

## Responsive Design
- Semua halaman sudah responsive untuk desktop/mobile

## Catatan
- Untuk export PDF, gunakan ekstensi PHP seperti FPDF jika dibutuhkan
- Untuk keamanan production, ganti password admin dan tambahkan validasi login lebih lanjut

## Kontak & Support
- Dokumentasi dan support: [email Anda]
