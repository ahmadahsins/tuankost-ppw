# TuanKost

TuanKost adalah aplikasi Laravel sederhana untuk menampilkan informasi kamar kos.

## Menjalankan aplikasi

1. Atur koneksi MySQL pada file `.env` dan gunakan database `tuankost-db`.
2. Jalankan `php artisan migrate`.
3. Jalankan `php artisan db:seed --class=RoomSeeder` untuk mengisi 20 kamar contoh. Seeder dapat dijalankan kembali tanpa menggandakan kamar yang sama.
4. Jalankan `php artisan serve`.
5. Buka alamat yang ditampilkan oleh Artisan pada browser.

Daftar kamar memiliki filter tipe, pencarian nomor kamar, dan statistik harga. Beranda menampilkan lima kamar terbaru. Data kamar diakses melalui model Eloquent `Room`.
