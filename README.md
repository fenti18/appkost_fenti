# Struktur Folder Aplikasi Web Manajemen Kos

```
appkost_fenti/
├── config/         # File konfigurasi (config.php)
├── controller/     # Logic aplikasi (PHP)
├── model/          # Model database (PHP)
├── public/         # Asset publik (css, js, images)
├── sql/            # File SQL setup database
├── view/           # Tampilan web (PHP/HTML)
└── README.md       # Dokumentasi
```

## Setup Database
1. Import file `sql/db_kost_setup.sql` ke MySQL.
2. Edit `config/config.php` jika perlu.

## Sample Data
Sudah tersedia di file SQL untuk testing.

## Koneksi
Gunakan `config/config.php` untuk koneksi database di file PHP lain.

## Pengembangan
- Tambahkan file PHP sesuai kebutuhan di folder controller, model, dan view.
- Asset web (CSS/JS) simpan di folder public.
