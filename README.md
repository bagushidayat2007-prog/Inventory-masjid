# Sistem Informasi Inventory dan Keuangan Masjid

Aplikasi web untuk membantu pengurus masjid mengelola data barang, stok, serta pemasukan dan pengeluaran keuangan.

Dibangun dengan **Laravel 10**, **PHP 8.1+**, **Blade**, **HTML5/CSS3/JavaScript murni** (tanpa React/Vue/Node), dan **MariaDB/MySQL**, dijalankan di **Laragon**.

## Fitur

- Autentikasi login/logout dengan session dan password hashing (`Hash::make`).
- Role Based Access Control: **Admin**, **Bendahara**, **Petugas Inventory** (middleware `role`).
- Dashboard dengan 7 kartu ringkasan (total barang, stok, barang masuk/keluar, pemasukan, pengeluaran, saldo), grafik keuangan & stok, stok rendah, aktivitas dan transaksi terbaru.
- CRUD Kategori, Supplier, Barang (search, filter, sorting, pagination).
- Barang Masuk: stok otomatis **bertambah** (`DB::transaction`).
- Barang Keluar: stok otomatis **berkurang**, ditolak dengan pesan **"Stok tidak mencukupi."** bila melebihi stok.
- Keuangan: pemasukan/pengeluaran dengan format `Rp 1.000.000` dan saldo otomatis.
- Audit Log setiap aktivitas penting.
- Laporan Inventory dan Laporan Keuangan dengan filter dan tombol cetak.
- Manajemen Pengguna dan edit Profil.

## Persyaratan

- PHP 8.1 atau lebih baru
- Composer
- MariaDB / MySQL (Laragon)
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`

## Instalasi

1. Install dependency PHP (Laravel 10 punya security advisory terbuka sehingga perlu menonaktifkan blocker audit):

   ```bash
   composer config audit.block-insecure false
   composer install
   ```

2. Salin `.env` dan buat application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Buat database `inventory_masjid` (jika belum ada):

   ```sql
   CREATE DATABASE IF NOT EXISTS inventory_masjid
     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. Pastikan konfigurasi database di `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_masjid
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi dan seeder:

   ```bash
   php artisan migrate --seed
   ```

6. Jalankan aplikasi (Laragon akan menyajikan otomatis, atau):

   ```bash
   php artisan serve
   ```

## Akun Default (hasil seeder)

| Username  | Password      | Role              |
| --------- | ------------- | ----------------- |
| admin     | admin123      | Admin             |
| bendahara | bendahara123  | Bendahara         |
| petugas   | petugas123    | Petugas Inventory |

> Ganti password default setelah login pertama melalui menu **Profile**.

## Hak Akses

| Menu                        | Admin | Bendahara | Petugas Inventory |
| --------------------------- | :---: | :-------: | :---------------: |
| Dashboard                   |  ✓    |    ✓      |        ✓          |
| Barang, Kategori, Supplier  |  ✓    |    –      |        ✓          |
| Barang Masuk / Keluar       |  ✓    |    –      |        ✓          |
| Keuangan                    |  ✓    |    ✓      |        –          |
| Laporan Inventory           |  ✓    |    –      |        ✓          |
| Laporan Keuangan            |  ✓    |    ✓      |        –          |
| Pengguna & Audit Log        |  ✓    |    –      |        –          |

## Struktur Database

10 tabel utama: `pengguna`, `kategori`, `supplier`, `barang`, `barang_masuk`, `detail_barang_masuk`, `barang_keluar`, `detail_barang_keluar`, `keuangan`, `audit_log`.

## Testing

```bash
php artisan test
```

Test menggunakan SQLite in-memory sehingga tidak mengganggu database development.

## Catatan

- Aset CSS/JS berada di `public/css/app.css` dan `public/js/app.js` dan dimuat langsung dengan `asset()`. Tidak ada proses build (Vite tidak dipakai saat runtime).
- Buka `AGENTS.md` untuk catatan arsitektur yang penting bagi pengembang.
