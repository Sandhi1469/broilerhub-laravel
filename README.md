# BroilerHub — Backend RESTful API

Backend RESTful API untuk platform e-commerce ayam broiler **BroilerHub**, dikembangkan menggunakan framework Laravel 13 dan Laravel Sanctum. Proyek ini disusun untuk memenuhi tugas praktikum mata kuliah Web Programming (Modul BAB III REST API) dengan fokus implementasi pada pengelolaan basis data relasional, CRUD data produk dan kategori, validasi request terpisah, manajemen file gambar pada storage lokal, serta autentikasi berbasis token.

---

## Gambaran Proyek

BroilerHub dirancang sebagai platform transaksi ayam broiler siap panen dan produk karkas/olahan daging ayam yang menghubungkan peternak (mitra kandang) dengan pembeli (konsumen rumah tangga, katering, dan pedagang pasar).

Arsitektur aplikasi memisahkan sisi *client* dan *server*:
- **Frontend:** Antarmuka Single Page Application (SPA) berbasis React yang dipublikasikan di Vercel ([https://react-market-ayam-broiler.vercel.app/](https://react-market-ayam-broiler.vercel.app/)).
- **Backend (repositori ini):** Layanan web service REST API berbasis Laravel yang mengelola data, logika bisnis, penyimpanan berkas, dan autentikasi pengguna.

---

## Teknologi yang Digunakan

Berdasarkan lingkungan pengembangan aktif proyek ini:
- **PHP:** Versi 8.3.33 (CLI x64)
- **Framework:** Laravel Framework 13.37.0
- **Autentikasi:** Laravel Sanctum 4.3.3 (Personal Access Token)
- **Database:** MySQL 8.x / MariaDB (Driver `mysql`, engine InnoDB)
- **Dependency Manager:** Composer 2.10.2
- **Testing & API Tooling:** Postman Collection v2.1.0

---

## Struktur Folder Utama

Berikut struktur direktori backend yang relevan dengan pengerjaan API ini:

```text
Broilerhub-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php        # Register, login, profile, logout
│   │   │       ├── CategoryController.php    # CRUD kategori produk
│   │   │       └── ProductController.php     # CRUD produk, filter, upload gambar
│   │   ├── Requests/                         # Form Request terpisah untuk validasi input
│   │   │   ├── LoginRequest.php
│   │   │   ├── RegisterRequest.php
│   │   │   ├── StoreCategoryRequest.php
│   │   │   ├── StoreProductRequest.php
│   │   │   ├── UpdateCategoryRequest.php
│   │   │   └── UpdateProductRequest.php
│   │   └── Resources/                        # Eloquent API Resource pembentuk respon JSON
│   │       ├── CategoryResource.php
│   │       └── ProductResource.php
│   └── Models/
│       ├── Category.php                      # Model kategori (PK: category_id)
│       ├── Product.php                       # Model produk (PK: product_id)
│       └── User.php                          # Model user terintegrasi trait HasApiTokens
├── bootstrap/
│   └── app.php                               # Konfigurasi routing API dan exception JSON
├── config/
│   ├── database.php
│   └── filesystems.php
├── database/
│   ├── migrations/                           # Skema tabel (users, categories, products, tokens)
│   └── seeders/                              # Data awal katalog broiler dan user default
├── postman/
│   ├── BroilerHub-API.postman_collection.json         # Koleksi request Postman
│   └── BroilerHub-Environment.postman_environment.json # Environment Postman
├── routes/
│   └── api.php                               # Rute API publik dan rute terproteksi Sanctum
├── storage/
│   └── app/public/products/                  # Direktori fisik berkas gambar produk
├── README.md
└── WALKTHROUGH.md                            # Panduan teknis langkah demi langkah
```

---

## Persyaratan Lingkungan Pengembangan

Sebelum menjalankan aplikasi, pastikan komputer Anda telah terpasang:
1. PHP >= 8.2 (disarankan PHP 8.3) dengan ekstensi aktif: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`.
2. Composer >= 2.2.
3. Server MySQL lokal (melalui Laragon, XAMPP, atau MySQL service langsung).
4. Postman Desktop App atau ekstensi Postman pada Visual Studio Code untuk pengujian API.

---

## Instalasi & Menjalankan Proyek

### 1. Kloning / Buka Folder Proyek
Masuk ke direktori root backend pada terminal Anda:
```bash
cd c:\laravel-projects\Broilerhub-laravel
```

### 2. Instalasi Dependency PHP
Unduh seluruh library yang dibutuhkan proyek:
```bash
composer install
```

### 3. Konfigurasi File Environment
Salin template konfigurasi `.env.example` menjadi `.env` jika belum tersedia:
```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan pengaturan koneksi database MySQL:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laraveltoko
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Ganti `DB_USERNAME` dan `DB_PASSWORD` sesuai dengan kredensial server MySQL di komputer Anda. Pastikan database dengan nama `laraveltoko` sudah dibuat terlebih dahulu di phpMyAdmin atau MySQL CLI.

### 4. Eksekusi Migrasi Database
Jalankan migrasi untuk membuat seluruh tabel yang dibutuhkan (`categories`, `products`, `personal_access_tokens`):
```bash
php artisan migrate
```

### 5. Pengisian Data Awal (Seeder)
Jalankan database seeder untuk mengisi data kategori broiler, contoh katalog produk, dan akun default admin:
```bash
php artisan db:seed
```
Seeder ini dirancang secara *idempotent* menggunakan `firstOrCreate` dan `updateOrCreate`, sehingga aman dijalankan berulang kali tanpa memicu galat duplikasi data.

Akun default yang terbentuk:
- **Email:** `admin@broilerhub.com`
- **Password:** `password123`

### 6. Hubungkan Penyimpanan Berkas Gambar
Buat link simbolik agar file gambar yang tersimpan di `storage/app/public` dapat diakses langsung oleh browser atau client frontend:
```bash
php artisan storage:link
```

### 7. Jalankan Server Pengembangan
Nyalakan server bawaan Laravel:
```bash
php artisan serve
```
Aplikasi backend akan aktif dan mendengarkan permintaan HTTP di `http://127.0.0.1:8000`.

---

## Ringkasan Endpoint API

Seluruh rute API memiliki prefix URL `/api`. Pengelompokan akses dibagi menjadi rute publik dan rute terproteksi token:

| HTTP Method | Endpoint | Akses | Fungsi Utama |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Publik (Throttle) | Mendaftarkan pengguna baru |
| `POST` | `/api/login` | Publik (Throttle) | Autentikasi dan penerbitan Bearer Token |
| `GET` | `/api/profile` | Terproteksi (`auth:sanctum`) | Melihat data profil pengguna yang sedang login |
| `POST` | `/api/logout` | Terproteksi (`auth:sanctum`) | Menghapus token aktif pengguna |
| `GET` | `/api/categories` | Publik | Melihat seluruh daftar kategori produk |
| `GET` | `/api/categories/{id}` | Publik | Melihat detail kategori beserta produk di dalamnya |
| `POST` | `/api/categories` | Terproteksi (`auth:sanctum`) | Menambahkan kategori baru |
| `PUT/PATCH`| `/api/categories/{id}` | Terproteksi (`auth:sanctum`) | Memperbarui nama kategori |
| `DELETE`| `/api/categories/{id}` | Terproteksi (`auth:sanctum`) | Menghapus kategori |
| `GET` | `/api/products` | Publik | Katalog produk (fitur pencarian, filter, sort, paginasi) |
| `GET` | `/api/products/{id}` | Publik | Menampilkan detail spesifik produk |
| `POST` | `/api/products` | Terproteksi (`auth:sanctum`) | Menambahkan produk baru (+ upload gambar) |
| `POST/PUT`| `/api/products/{id}` | Terproteksi (`auth:sanctum`) | Memperbarui data produk (+ ganti gambar) |
| `DELETE`| `/api/products/{id}` | Terproteksi (`auth:sanctum`) | Menghapus produk (+ hapus berkas gambar fisik) |

---

## Pengujian Menggunakan Postman

Berkas pengujian siap impor telah disediakan di dalam folder `postman/`:
1. **`BroilerHub-API.postman_collection.json`**: Memuat 24 skenario pengujian yang dikelompokkan ke dalam 6 folder (*Authentication*, *Categories*, *Products CRUD*, *Product Search and Filters*, *File Upload*, serta *Authorization and Error Handling*).
2. **`BroilerHub-Environment.postman_environment.json`**: Menyimpan variabel `baseUrl` (`http://127.0.0.1:8000`) dan variabel `token`.

### Alur Singkat di Postman:
1. Buka Postman -> klik **Import** -> pilih kedua file di folder `postman/`.
2. Pilih environment **BroilerHub Local Environment** di pojok kanan atas.
3. Jalankan request **Login - Valid Credentials (200 OK)** pada folder `1. Authentication`.
4. Skrip test otomatis akan menangkap token Sanctum dan menyimpannya ke variabel `token`.
5. Semua request berikutnya yang memerlukan autentikasi akan langsung menggunakan token tersebut tanpa perlu *copy-paste* manual.

---

## Panduan Teknis Lengkap

Untuk panduan alur pengerjaan teknis langkah demi langkah, penjelasan struktur kode, logika pencegahan *orphan file*, pengujian via terminal, dan pemecahan kendala umum, silakan baca dokumentasi pendamping di:
👉 **[WALKTHROUGH.md](file:///c:/laravel-projects/Broilerhub-laravel/WALKTHROUGH.md)**

---

## Catatan Keterbatasan Proyek Saat Ini

Sesuai ruang lingkup tugas Modul Praktikum BAB III:
1. **Frontend Integration:** Frontend React di Vercel saat ini masih menggunakan mock state lokal untuk keranjang dan pesanan. Modul backend ini menyediakan fondasi REST API katalog, kategori, file storage, dan autentikasi user.
2. **Modul Transaksi:** Tabel `orders` dan integrasi *payment gateway* belum diimplementasikan pada backend karena berada di luar cakupan materi BAB III (difokuskan pada CRUD produk, kategori, manajemen file, dan token Sanctum).
3. **Role Management:** Sistem hak akses saat ini membedakan user terautentikasi (melalui Bearer Token Sanctum) untuk operasi tulis (*store*, *update*, *delete*) dan pengunjung umum untuk operasi baca (*read-only catalog*), belum menggunakan Role-Based Access Control (RBAC) bertingkat.
