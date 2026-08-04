# Diagram Mermaid — Sistem Manajemen Koperasi Desa Merah Putih

## 1. Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ TRANSAKSI : mencatat
    ANGGOTA ||--o{ TRANSAKSI : melakukan
    KATEGORI_BARANG ||--o{ BARANG : memiliki
    BARANG ||--o{ TRANSAKSI_DETAIL : terjual
    TRANSAKSI ||--o{ TRANSAKSI_DETAIL : berisi

    USERS {
        int id PK
        string name
        string email
        string password
        string role
        datetime created_at
    }

    KATEGORI_BARANG {
        int id PK
        string nama_kategori
        datetime created_at
    }

    BARANG {
        int id PK
        int kategori_id FK
        string nama_barang
        int harga_beli
        int harga_jual
        int stok
        string satuan
        datetime created_at
    }

    ANGGOTA {
        int id PK
        string nik
        string nama
        string alamat
        string no_hp
        date tanggal_gabung
        datetime created_at
    }

    TRANSAKSI {
        int id PK
        string kode_transaksi
        int user_id FK
        int anggota_id FK
        int total_bayar
        int bayar
        int kembalian
        datetime created_at
    }

    TRANSAKSI_DETAIL {
        int id PK
        int transaksi_id FK
        int barang_id FK
        int harga_satuan
        int qty
        int subtotal
    }
```

---

## 2. Use Case Diagram

```mermaid
flowchart LR
    Admin([Admin])
    Kasir([Kasir])

    subgraph Sistem Koperasi
        UC1(Login)
        UC2(Kelola Kategori Barang)
        UC3(Kelola Barang / Stok)
        UC4(Kelola Anggota)
        UC5(Kelola User)
        UC6(Transaksi Penjualan)
        UC7(Lihat Riwayat Transaksi)
        UC8(Lihat Laporan Penjualan)
        UC9(Lihat Stok Barang)
    end

    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC7
    Admin --> UC8

    Kasir --> UC1
    Kasir --> UC6
    Kasir --> UC7
    Kasir --> UC9
```

---

## 3. Alur Transaksi Penjualan (Flowchart)

```mermaid
flowchart TD
    A[Kasir mulai transaksi baru] --> B[Cari / pilih barang]
    B --> C[Input jumlah qty]
    C --> D{Stok cukup?}
    D -- Tidak --> E[Tampilkan peringatan stok tidak cukup]
    E --> B
    D -- Ya --> F[Tambah ke keranjang]
    F --> G{Tambah barang lain?}
    G -- Ya --> B
    G -- Tidak --> H[Hitung total belanja]
    H --> I[Input jumlah bayar]
    I --> J[Hitung kembalian]
    J --> K[Simpan transaksi]
    K --> L[Kurangi stok barang otomatis]
    L --> M[Tampilkan struk]
```

---

### Cara Pakai File Ini

- Bisa dibuka langsung di editor yang mendukung Mermaid (VS Code dengan extension "Markdown Preview Mermaid Support", Obsidian, atau GitHub — GitHub otomatis render blok ```mermaid```)
- Bisa juga di-paste ke [mermaid.live](https://mermaid.live) untuk lihat visualisasinya secara online dan export ke PNG/SVG
