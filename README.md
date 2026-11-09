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
| GET | `/api/genres` | Daftar genre beserta buku |
| POST | `/api/genres` | Membuat genre |
| GET | `/api/genres/{id}` | Detail genre beserta buku |
| PUT/PATCH | `/api/genres/{id}` | Memperbarui genre |
| DELETE | `/api/genres/{id}` | Menghapus genre yang belum dipakai buku |
| GET | `/api/books` | Daftar buku beserta penulis |
| POST | `/api/books` | Membuat buku |
| GET | `/api/books/{id}` | Detail buku beserta penulis |
| PUT/PATCH | `/api/books/{id}` | Memperbarui buku |
| DELETE | `/api/books/{id}` | Menghapus buku |

Penulis memerlukan `name` dan `email` unik. Genre memerlukan `name` unik. Buku memerlukan `author_id`, `genre_id`, `title`, dan `price`; ID penulis dan genre harus merujuk ke data yang ada. Genre yang masih dipakai buku tidak bisa dihapus (HTTP 409). Data buku lama yang belum mempunyai genre dipindahkan ke genre `Uncategorized`.

## Pengujian dengan Postman

Import [`postman/BookSales API.postman_collection.json`](postman/BookSales%20API.postman_collection.json) ke Postman. Jalankan request secara berurutan menggunakan Collection Runner. Collection membuat, membaca, memperbarui, lalu menghapus penulis, genre, dan buku; ID tersimpan otomatis sebagai variabel collection.

Materi **Show, Update, Destroy Data** diuji melalui request `GET /api/books/{id}`, `PATCH /api/books/{id}`, dan `DELETE /api/books/{id}` di collection. Update biasa menggunakan JSON dan mengembalikan HTTP 200; hapus mengembalikan HTTP 204. ID yang tidak ditemukan mengembalikan HTTP 404.

Untuk mengganti cover buku di Postman, kirim `POST {{baseUrl}}/api/books/{{bookId}}` dengan **Body → form-data**. Tambahkan `_method` bertipe **Text** bernilai `PATCH`, lalu `cover_photo` bertipe **File** dan pilih gambar JPG, JPEG, PNG, atau WebP (maksimal 2 MB). Penggunaan method spoofing memastikan file multipart terbaca PHP saat mengirim update. Setelah update berhasil, file cover lama di `book-covers/` dihapus. Menghapus buku juga menghapus file cover yang diunggah.

Tes otomatis juga dapat dijalankan dengan:

```powershell
php artisan test --compact
```
