# Pengelolaan KDMP

![Status](https://img.shields.io/badge/Status-Work%20In%20Progress-orange?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=for-the-badge&logo=codeigniter)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)



> **Peringatan (Work in Progress):**
> Aplikasi ini masih dalam tahap pengembangan aktif. Fitur, struktur database, dan endpoint API belum stabil serta dapat berubah sewaktu-waktu.

---

## Gambaran Umum

**Sistem Manajemen Koperasi Desa Merah Putih** adalah aplikasi berbasis web yang dirancang untuk mengelola operasional harian koperasi desa. Aplikasi ini mencakup pencatatan data inventaris barang, pendataan anggota koperasi, serta pengelolaan transaksi penjualan oleh kasir (kalkulasi kembalian, cetak struk, dan pemotongan stok otomatis).

Aplikasi ini dibangun menggunakan CodeIgniter 4 dengan menerapkan arsitektur Clean Code (Entity, Model, Service) serta pembatasan hak akses berbasis peran (Role-Based Access Control: Admin dan Kasir).

---

## Progress Pengembangan (Roadmap)

- [x] Setup Proyek dan Database: Migration & Seeder dasar.
- [x] Authentication: Login + Hash Password via Entity Mutator.
- [ ] Manajemen Katalog dan Produk
  - [ ] CRUD Kategori Produk.
  - [ ] CRUD Produk (Nama, Harga Beli, Harga Jual, Foto, Kode Barcode/SKU).
- [ ] Manajemen Stok (Inventory Control)
  - [ ] Penyesuaian Stok Masuk (Stock In/Restock) dengan pencatatan riwayat (bukan edit variabel stok langsung).
  - [ ] Log/Riwayat Perubahan Stok (Masuk, Keluar, Terjual via Kasir, dan Penyesuaian/Rusak).
- [ ] Sistem Kasir / Point of Sale (POS)
  - [ ] Antarmuka Kasir (Pencarian produk, Scan Barcode, Keranjang/Cart).
  - [ ] Ketersediaan Stok Real-time (Mencegah transaksi jika stok habis).
  - [ ] Kategori Pembayaran (Tunai, QRIS, Simpanan/Saldo Anggota Koperasi jika ada).
  - [ ] Cetak Struk/Nota Transaksi (Thermal Printer / Print Friendly PDF).
- [ ] Manajemen Anggota (Fitur Khas Koperasi)
  - [ ] Data Anggota Koperasi (Diskon khusus, Poin, Pencatatan transaksi per anggota).
- [ ] Pelaporan dan Analitik (Reporting)
  - [ ] Riwayat Transaksi Penjualan.
  - [ ] Export PDF & Excel Laporan Transaksi/Penjualan Periodik.
  - [ ] Export PDF Laporan Rekapitulasi Stok dan Keuntungan.
- [ ] Hak Akses dan Otorisasi (RBAC)
  - [ ] Pemisahan Akses (Admin: Full Access | Kasir: Hanya POS dan Stok Masuk).
- [ ] Testing dan Polish
  - [ ] Automated Unit Testing.
  - [ ] Refactoring dan Bug Fixing.

---

## Hak Akses User

| Role | Hak Akses Fitur |
| :--- | :--- |
| **Admin** | Kelola Data User, Barang, Kategori, Anggota, serta Laporan Penjualan. |
| **Kasir** | Transaksi Penjualan (POS), Cek Stok Barang, dan Riwayat Transaksi. |

---

## Tech Stack dan Prasyarat

- Framework: CodeIgniter 4.x
- Bahasa Pemrograman: PHP >= 8.2 (Ekstensi: intl, mbstring, curl)
- Database: MySQL 8.0 / MariaDB 10.4
- Dependency Manager: Composer >= 2.0
- Frontend Build: Node.js + npm (Tailwind CSS CLI)

---

## Cara Menjalankan di Komputer Lokal (Quick Start)

Langkah-langkah instalasi berikut disusun secara berurutan agar aplikasi dapat dijalankan dengan mudah di komputer lain.

### 1. Clone Repositori

```bash
git clone https://github.com/Devangaa/Pengelolaan-KDMP
cd nama-proyek
```

### 2. Install Dependensi (Composer)

```bash
composer install
```

### 3. Konfigurasi Environment (.env)

Jika belum ada, salin file template `env` menjadi `.env`:

```bash
cp env .env
```

Buka file `.env` dan sesuaikan pengaturan database lokal:

```ini
database.default.hostname = localhost
database.default.database = db_nama_proyek
database.default.username = root
database.default.password =
```

### 4. Jalankan Database Migration dan Seeder

```bash
php spark migrate
php spark db:seed MasterSeeder
```

### 5. Jalankan Development Server

Terminal 1 (Backend):

```bash
php spark serve
```

Terminal 2 (Frontend):

```bash
npm run dev
```

Akses aplikasi melalui browser/Postman di: `http://localhost:8080`

---

## Ringkasan Arsitektur Kode

Penjelasan singkat mengenai struktur kode pada proyek ini:

- **Entities (`app/Entities/`):** Mengelola logika bisnis atribut dan transformasi data individual, misalnya hashing password user secara otomatis di `setPassword()`, format mata uang Rupiah pada `harga_jual`, dan kalkulasi subtotal transaksi.
- **Models (`app/Models/`):** Menangani interaksi dan kueri database, misalnya kueri stok barang, SQL JOIN antara transaksi dan detail transaksi, serta pemotongan stok otomatis.
- **Services (`app/Services/`):** Alat bantu/utilitas global yang digunakan lintas modul, misalnya service pencetak struk/PDF, ekspor laporan penjualan, dan manajemen session/auth.
- **Controllers (`app/Controllers/`):** Mengatur alur utama yang menerima request dari user (Admin/Kasir), memanggil Model/Entity/Service terkait, dan mengembalikan response (View/JSON).

Dokumentasi teknis dapat dilihat langsung dari struktur folder `app/`, khususnya `app/Entities`, `app/Models`, `app/Controllers`, dan `app/Config`.

---

## Tim dan Kontribusi

**Pengembang:** Devangaa ([@Devangaa](https://github.com/Devangaa))
