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

## Persyaratan Lingkungan Pengembangan

Sebelum menjalankan aplikasi, pastikan komputer telah terpasang:
1. PHP >= 8.2 (disarankan PHP 8.3) dengan ekstensi aktif: `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`, `curl`.
2. Composer >= 2.2.
3. Server MySQL lokal (melalui Laragon, XAMPP, atau MySQL service langsung).
4. Postman Desktop App atau ekstensi Postman pada Visual Studio Code untuk pengujian API.

## Panduan Teknis Lengkap

Untuk panduan alur pengerjaan teknis langkah demi langkah, penjelasan struktur kode, logika pencegahan *orphan file*, pengujian via terminal, dan pemecahan kendala umum, silakan baca dokumentasi pendamping di:
👉 **[WALKTHROUGH.md](file:///c:/laravel-projects/Broilerhub-laravel/WALKTHROUGH.md)**

---

## Catatan Keterbatasan Proyek

Sesuai ruang lingkup tugas Modul Praktikum:
1. **Frontend Integration:** Frontend React di Vercel saat ini masih belum terintegrasi dan masih menggunakan mock state lokal untuk keranjang dan pesanan. Modul backend ini menyediakan fondasi REST API katalog, kategori, file storage, dan autentikasi user.
2. **Role Management:** Sistem hak akses saat ini membedakan user terautentikasi (melalui Bearer Token Sanctum) untuk operasi tulis (*store*, *update*, *delete*) dan pengunjung umum untuk operasi baca (*read-only catalog*), belum menggunakan Role-Based Access Control (RBAC) bertingkat.
