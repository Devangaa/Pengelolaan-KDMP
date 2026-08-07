# PENGELOLAAN KDMP

<!-- BAGIAN 1: BADGE & WARNING STATUS DEVELOPMENT -->
![Status](https://img.shields.io/badge/Status-Work%20In%20Progress-orange?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=for-the-badge&logo=codeigniter)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)

> ⚠️ **PERINGATAN (WORK IN PROGRESS):**  
> Aplikasi ini masih dalam **tahap pengembangan aktif**. Fitur, struktur database, dan endpoint API belum stabil serta dapat berubah sewaktu-waktu.

---

## 📌 Gambaran Umum (Overview)

**Sistem Manajemen Koperasi Desa Merah Putih** adalah aplikasi berbasis web yang dirancang untuk mengelola operasional harian koperasi desa. Aplikasi ini mencakup pencatatan data inventaris barang, pendataan anggota koperasi, serta pengelolaan transaksi penjualan oleh kasir (kalkulasi kembalian, cetak struk, dan pemotongan stok otomatis).

Aplikasi ini dibangun menggunakan **CodeIgniter 4** dengan menerapkan arsitektur *Clean Code* (Entity, Model, Service) serta pembatasan hak akses berbasis peran (*Role-Based Access Control*: Admin & Kasir).

---

## 🚧 Progress Pengembangan (Roadmap / To-Do List)

- [x] **Setup Proyek & Database:** Migration & Seeder dasar.
- [ ] **Authentication:** Register & Login (JWT) + Hash Password via **Entity Mutator**.
- [ ] **Manajemen Produk:** CRUD Katalog & Filter Kategori.
- [ ] **Pelaporan:** Export PDF Laporan Transaksi.
- [ ] **Testing:** Automated Unit Testing.

---

## 👥 Hak Akses User

| Role | Hak Akses Fitur |
| :--- | :--- |
| **Admin** | Kelola Data User, Barang, Kategori, Anggota, serta Laporan Penjualan. |
| **Kasir** | Transaksi Penjualan (POS), Cek Stok Barang, dan Riwayat Transaksi. |

---

## 🛠️ Tech Stack & Prasyarat

- **Framework:** CodeIgniter 4.x
- **Bahasa Pemrograman:** PHP `>= 8.1` / `8.2` (Ekstensi: `intl`, `mbstring`, `curl`)
- **Database:** MySQL 8.0 / MariaDB 10.4
- **Dependency Manager:** Composer `>= 2.0`

---

## 🚀 Cara Menjalankan di Komputer Lokal (Quick Start)

Tuliskan langkah-langkah instalasi secara berurutan agar siapa saja (termasuk kamu di komputer lain) bisa langsung menjalankannya:

### 1. Clone Repositori
```bash
git clone [https://github.com/Devangaa/Pengelolaan-KDMP](https://github.com/Devangaa/Pengelolaan-KDMP)
cd nama-proyek
```

### 2. Install Dependensi (Composer)
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp env .env
```
Buka file `.env` dan sesuaikan pengaturan database lokal kamu:
```ini
database.default.hostname = localhost
database.default.database = db_nama_proyek
database.default.username = root
database.default.password = 
```

### 4. Jalankan Database Migration & Seeder

```bash
php spark migrate
php spark db:seed MasterSeeder
```

### 5. Jalankan Development Server
Terminal 1 (Backend):
```bash
php spark serve
```

Terminal 2 (Fronted):
```bash
npm run dev
```

Akses aplikasi melalui browser/Postman di: `http://localhost:8080`

---

## 📐 Ringkasan Arsitektur Kode

Penjelasan singkat tentang bagaimana kodingan diatur di proyek ini:

* **Entities (`app/Entities/`):** Mengelola logika bisnis atribut dan transformasi data individual (misal: otomatis meng-hash password user di `setPassword()`, format mata uang Rupiah `harga_jual`, dan kalkulasi `subtotal` transaksi)[cite: 2].
* **Models (`app/Models/`):** Menangani interaksi dan kueri database (misal: kueri stok barang, SQL `JOIN` antara transaksi dan detail transaksi, serta pemotongan stok otomatis)[cite: 2].
* **Services (`app/Services/`):** Alat bantu/utilitas global yang digunakan lintas modul (misal: Service pencetak struk/PDF, ekspor laporan penjualan, dan manajemen session/auth).
* **Controllers (`app/Controllers/`):** Pengatur alur utama yang menerima *Request* dari user (Admin/Kasir), memanggil Model/Entity/Service terkait, dan mengembalikan *Response* (View/JSON)[cite: 2].

> 📖 *Dokumentasi teknis lebih detail mengenai alur data dapat dibaca di file [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md).*

---

## 👥 Tim & Kontribusi

* **Pengembang:** [Devangaa] ([@Devangaa](https://github.com/Devangaa))