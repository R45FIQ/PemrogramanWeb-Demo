# FlipPlay - RESTful API Platform (Laravel 12)
> **Praktikum Pemrograman Web - Modul 4**  
> **Laboratorium Informatika - Universitas Muhammadiyah Malang (UMM)**

---

## 📌 1. Deskripsi Project

**FlipPlay** adalah platform hiburan digital all-in-one untuk katalog **Anime, Manga, dan Drama**. 

Pada **Modul 3**, FlipPlay dikembangkan sebagai simulasi backend berbasis terminal menggunakan prinsip Pemrograman Berorientasi Objek (OOP) murni PHP (Class, Inheritance, Encapsulation, Polymorphism).

Pada **Modul 4**, FlipPlay berevolusi menjadi arsitektur backend modern berbasis **RESTful API** menggunakan **Laravel 12**, basis data relasional **MySQL**, **Eloquent ORM**, **Many-to-Many Relationship**, **Form Request Validation**, dan **API Resource**.

---

## 🏛️ 2. Arsitektur & Relasi Database

### A. Tabel `users`
Menyimpan data akun pengguna platform FlipPlay.
- `id` (BIGINT, Primary Key, Auto Increment)
- `name` (VARCHAR 255)
- `email` (VARCHAR 255, Unique)
- `password` (VARCHAR 255)
- `timestamps` (`created_at`, `updated_at`)

### B. Tabel `media`
Menyimpan katalog media hiburan dengan mempertahankan seluruh entitas dari Modul 3 (Anime, Manga, Drama).
- `id` (BIGINT, Primary Key, Auto Increment)
- `title` (VARCHAR 255, Unique)
- `type` (VARCHAR 50 — `Anime`, `Manga`, `Drama`)
- `genre` (VARCHAR 255)
- `rating` (DECIMAL 3,1, Default 0.0)
- `release_year` (INT, Nullable)
- `description` (TEXT, Nullable)
- `episodes` (INT, Nullable — Atribut spesifik Anime/Drama)
- `chapters` (INT, Nullable — Atribut spesifik Manga)
- `author` (VARCHAR 255, Nullable — Mangaka)
- `studio` (VARCHAR 255, Nullable — Studio Animasi)
- `country` (VARCHAR 255, Nullable — Negara Asal Drama)
- `timestamps` (`created_at`, `updated_at`)

### C. Pivot Table `media_user`
Menghubungkan pengguna dengan media yang disimpan ke dalam koleksi pribadinya (**Many-to-Many Relationship**).
- `id` (BIGINT, Primary Key, Auto Increment)
- `user_id` (BIGINT, Foreign Key ke `users.id`, `onDelete('cascade')`)
- `media_id` (BIGINT, Foreign Key ke `media.id`, `onDelete('cascade')`)
- `timestamps` (`created_at`, `updated_at`)
- **Constraint**: `UNIQUE(user_id, media_id)` untuk menjamin satu media tidak terduplikasi pada koleksi pengguna yang sama.

---

## ⚙️ 3. Prasyarat Sistem

- **PHP**: Versi `>= 8.2` (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`)
- **Composer**: Versi 2.x
- **MySQL Database Server**: Port 3306 (Laragon / XAMPP)
- **Postman**: Untuk pengujian endpoint API

---

## 🚀 4. Panduan Instalasi & Menjalankan Project

### Langkah 1: Clone / Siapkan Project
Buka terminal pada direktori project:
```bash
cd "c:/Users/Lenovo/Pemrograman Web/Modul 4/Demo"
```

### Langkah 2: Konfigurasi Environment `.env`
Salin template konfigurasi dan sesuaikan pengaturan koneksi MySQL:
```bash
cp .env.example .env
```
Pastikan pengaturan koneksi database di `.env` sesuai dengan server MySQL lokal:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flipplay_db
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 3: Buat Database MySQL
Buat database baru di MySQL (via HeidiSQL, phpMyAdmin, atau MySQL CLI):
```sql
CREATE DATABASE IF NOT EXISTS flipplay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 4: Generate Application Key
```bash
php artisan key:generate
```

### Langkah 5: Jalankan Migration & Database Seeder
Jalankan migrasi seluruh tabel sekaligus menyisipkan data awal (termasuk data katalog dan akun dari Modul 3):
```bash
php artisan migrate:fresh --seed
```

### Langkah 6: Jalankan Server Development Laravel
```bash
php artisan serve
```
Aplikasi backend API akan berjalan pada: `http://127.0.0.1:8000`.

---

## 📚 5. Menjalankan Kode Modul 3 (Legacy OOP)
Source code Modul 3 tetap dipertahankan secara utuh pada folder `src/` dan file `index.php`. Anda dapat menjalankan simulasi OOP Modul 3 kapan saja melalui terminal:
```bash
php index.php
```

---

## 📡 6. Dokumentasi Lengkap Endpoint REST API

Base URL: `http://127.0.0.1:8000/api`

### A. Media CRUD API (`/api/media`)

| Method | Endpoint | Deskripsi | Status Code |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/media` | Menampilkan seluruh media dengan pagination & filter | `200 OK` |
| **GET** | `/api/media?search={keyword}` | Mencari media berdasarkan judul | `200 OK` |
| **GET** | `/api/media?type={Anime\|Manga\|Drama}` | Filter media berdasarkan jenis media | `200 OK` |
| **GET** | `/api/media/{id}` | Menampilkan detail 1 media beserta pengguna yang mengoleksinya | `200 OK` / `404 Not Found` |
| **POST** | `/api/media` | Menambahkan data media baru | `201 Created` / `422 Unprocessable` |
| **PATCH** | `/api/media/{id}` | Memperbarui sebagian data media (Partial Update) | `200 OK` / `422 Unprocessable` |
| **DELETE**| `/api/media/{id}` | Menghapus media dari katalog dan membersihkan relasi pivot | `200 OK` / `404 Not Found` |

#### Contoh Request Body POST `/api/media`:
```json
{
    "title": "Solo Leveling",
    "type": "Anime",
    "genre": "Action Fantasy",
    "rating": 8.5,
    "release_year": 2024,
    "description": "Sung Jin-woo bangkit menjadi hunter terkuat di dunia.",
    "episodes": 12,
    "studio": "A-1 Pictures"
}
```

---

### B. User Collection Custom Actions (`/api/users/{user}/collection`)

| Method | Endpoint | Deskripsi | Status Code |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/users/{user}/collection` | Menampilkan seluruh media di koleksi pengguna | `200 OK` |
| **POST** | `/api/users/{user}/collection/{media}` | Menambahkan media ke koleksi pengguna (**attach**) | `201 Created` / `409 Conflict` |
| **DELETE** | `/api/users/{user}/collection/{media}` | Menghapus media dari koleksi pengguna (**detach**) | `200 OK` / `404 Not Found` |

---

## 🧪 7. Panduan Pengujian Postman

File koleksi Postman lengkap telah disediakan di root project:  
📁 `FlipPlay_API.postman_collection.json`

### Cara Import ke Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas.
3. Pilih atau drag-and-drop file `FlipPlay_API.postman_collection.json`.
4. Variabel `{{base_url}}` secara otomatis diset ke `http://127.0.0.1:8000`.

### Skenario Pengujian yang Tersedia:
1. **Media CRUD**:
   - `GET` Seluruh Media (Pagination) `[200 OK]`
   - `GET` Detail Media (Eager Loading Users) `[200 OK]`
   - `POST` Tambah Media Baru (Happy Path) `[201 Created]`
   - `POST` Media - Judul Kosong (Validation Failure) `[422 Unprocessable Entity]`
   - `POST` Media - Judul Duplikat `[422 Unprocessable Entity]`
   - `PATCH` Update Media (Partial Update) `[200 OK]`
   - `PATCH` Update Media - Judul Duplikat Media Lain `[422 Unprocessable Entity]`
   - `DELETE` Hapus Media `[200 OK]`
   - `GET` Media ID Tidak Ditemukan `[404 Not Found]`
   - `GET` Media dengan Pencarian `?search=Titan` `[200 OK]`
2. **User Collection**:
   - `POST` Tambah Media ke Koleksi (Attach) `[201 Created]`
   - `POST` Tambah Media Sama ke Koleksi (Duplicate / Conflict) `[409 Conflict]`
   - `GET` Koleksi Pengguna `[200 OK]`
   - `DELETE` Hapus Media dari Koleksi (Detach) `[200 OK]`
   - `DELETE` Hapus Media yang Belum Ada di Koleksi `[404 Not Found]`
   - `POST/DELETE` Akses User/Media ID Salah `[404 Not Found]`

---

## 🧪 8. Pengujian Otomatis (Automated Testing)

Project ini dilengkapi dengan test suite lengkap berbasis PHPUnit / Laravel Testing yang menguji seluruh 16 skenario fungsionalitas dan validasi:
```bash
php artisan test
```
**Hasil Verifikasi**: `18 passed (97 assertions)`.
