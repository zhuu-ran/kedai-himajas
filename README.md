# Kedai Himajas - Sistem Pemesanan Makanan

Aplikasi web untuk pemesanan makanan langsung dari meja pelanggan, dilengkapi dengan panel admin untuk mengelola menu dan pesanan.

## Fitur

- **Landing Page** — menampilkan menu makanan dengan filter kategori (Ayam, Mie, Cemilan/Dessert, Minuman)
- **Keranjang & Checkout** — pelanggan dapat memilih menu, mengisi data pemesanan (nomor meja, nama, telepon), dan mengirim pesanan
- **Autentikasi** — login dan register untuk admin/staf
- **Admin Panel**
  - Dashboard statistik (total menu, total pesanan, pesanan pending, total pengguna)
  - Kelola Menu (tambah, edit, hapus menu beserta gambar)
  - Kelola Pesanan (lihat daftar pesanan, ubah status: pending → confirmed → completed/cancelled)

## Teknologi

- PHP (native, tanpa framework)
- MySQL / MariaDB
- Bootstrap 5
- JavaScript (Vanilla JS + jQuery)
- AOS (Animate On Scroll)

## Struktur Folder

```
├── index.php              # Landing page
├── checkout.php           # Halaman keranjang & checkout
├── proses_order.php       # Handler proses simpan pesanan ke database
├── config/
│   └── database.php       # Konfigurasi koneksi database
├── admin/
│   ├── dashboard.php      # Dashboard admin
│   ├── menu.php           # Kelola menu
│   └── pesanan.php        # Kelola pesanan
├── auth/
│   ├── process.php        # Handler login & register
│   └── logout.php         # Handler logout
└── assets/
    ├── css/
    ├── js/
    └── img/
```

## Instalasi Lokal (XAMPP)

1. Clone repository ini ke folder `htdocs`:
   ```
   git clone https://github.com/username/nama-repo.git
   ```
2. Buat database baru bernama `pemesanan_makanan` melalui phpMyAdmin
3. Import file `pemesanan_makanan.sql` ke database tersebut
4. Sesuaikan kredensial database di `config/database.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $database = "pemesanan_makanan";
   ```
5. Jalankan Apache & MySQL melalui XAMPP Control Panel
6. Akses melalui browser: `http://localhost/nama-folder/index.php`

## Struktur Database

| Tabel | Keterangan |
|---|---|
| `menus` | Data menu makanan (nama, kategori, harga, gambar, status) |
| `orders` | Data pesanan (nomor meja, nama pemesan, total harga, status) |
| `order_items` | Detail item per pesanan (relasi ke `menus` dan `orders`) |
| `users` | Data akun admin/staf |

## Catatan

- Pembayaran dilakukan langsung di kasir/staf, bukan melalui sistem (belum ada integrasi payment gateway)
- Keranjang belanja sementara disimpan di `localStorage` browser sebelum checkout

## Kontributor

Kelompok 3 — RPL
