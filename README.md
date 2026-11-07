# BookSales API

REST API untuk mengelola buku dan penulis menggunakan Laravel.

## Menjalankan aplikasi

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Server berjalan di `http://127.0.0.1:8000`. Semua endpoint berada di prefix `/api` dan mengembalikan JSON.

## Endpoint

| Method | Endpoint | Keterangan |
| --- | --- | --- |
| GET | `/api/authors` | Daftar penulis dan buku |
| POST | `/api/authors` | Membuat penulis |
| GET | `/api/authors/{id}` | Detail penulis dan buku |
| PUT/PATCH | `/api/authors/{id}` | Memperbarui penulis |
| DELETE | `/api/authors/{id}` | Menghapus penulis |
| GET | `/api/books` | Daftar buku beserta penulis |
| POST | `/api/books` | Membuat buku |
| GET | `/api/books/{id}` | Detail buku beserta penulis |
| PUT/PATCH | `/api/books/{id}` | Memperbarui buku |
| DELETE | `/api/books/{id}` | Menghapus buku |

Penulis memerlukan `name` dan `email` unik. Buku memerlukan `author_id`, `title`, dan `price`; `author_id` harus merujuk ke penulis yang ada.

## Pengujian dengan Postman

Import [`postman/BookSales API.postman_collection.json`](postman/BookSales%20API.postman_collection.json) ke Postman. Jalankan request secara berurutan menggunakan Collection Runner. Collection membuat, membaca, memperbarui, lalu menghapus penulis dan buku; ID tersimpan otomatis sebagai variabel collection.

Tes otomatis juga dapat dijalankan dengan:

```powershell
php artisan test --compact
```
