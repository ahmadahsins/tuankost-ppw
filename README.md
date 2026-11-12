# TuanKost

TuanKost adalah aplikasi Laravel sederhana untuk mengelola informasi kamar kos.

## Menjalankan aplikasi

1. Jalankan `composer install`, salin `.env.example` ke `.env` jika belum ada, lalu atur koneksi MySQL ke database `tuankost-db`. Jalankan `php artisan key:generate` hanya untuk instalasi baru.
2. Jalankan `php artisan migrate`.
3. Jalankan `php artisan db:seed --class=RoomSeeder` untuk mengisi 20 kamar contoh. Seeder dapat dijalankan kembali tanpa menggandakan kamar yang sama.
4. Jalankan `php artisan serve`.
5. Buka alamat yang ditampilkan oleh Artisan pada browser.
6. Buka `/register` untuk membuat akun, lalu gunakan akun tersebut untuk login. Registrasi yang berhasil langsung mengarahkan pengguna ke dashboard.

Daftar kamar memiliki filter tipe, pencarian nomor kamar, dan statistik harga. Beranda menampilkan lima kamar terbaru. Data kamar diakses melalui model Eloquent `Room`.

## Authentication (Pertemuan 9)

Laravel Fortify menangani registrasi, login berbasis email, ingat saya, dan logout. Beranda serta halaman tentang dapat diakses publik. Dashboard dan seluruh route pengelolaan kamar dilindungi middleware `auth`, termasuk request tambah, ubah, dan hapus.

Setiap akun terdaftar memiliki akses pengelolaan yang sama pada tahap ini; belum ada pembagian peran. Fitur reset password, verifikasi email, two-factor authentication, dan passkey belum diaktifkan. Password disimpan sebagai hash, dan login dibatasi lima percobaan per menit untuk kombinasi email dan alamat IP.

Tampilan menggunakan Bootstrap 5.3.8 dari CDN dengan tema biru TuanKost. Koneksi internet diperlukan untuk memuat Bootstrap. Gunakan `APP_LOCALE=id` untuk pesan autentikasi dan validasi berbahasa Indonesia.

## Foto kamar

Aktifkan ekstensi GD di PHP, lalu jalankan `composer install`, `php artisan migrate`, dan `php artisan storage:link`. Setelah login, buka **Edit Kamar** untuk mengunggah atau menghapus foto. Thumbnail juga ditampilkan di halaman detail; klik thumbnail untuk membuka versi lebih besar.

Unggahan mendukung JPEG, PNG, GIF, dan WebP, maksimal lima gambar per permintaan, 2 MB dan 4000 × 4000 piksel per gambar. Intervention Image menyimpan versi JPEG dengan lebar maksimal 1000 piksel dan thumbnail maksimal 300 piksel pada disk `public`. Foto dapat diakses melalui URL publiknya, sedangkan operasi unggah dan hapus memerlukan login.

## Pengujian

Jalankan `php artisan test`. Konfigurasi pengujian memakai SQLite dalam memori dan tidak mengubah data MySQL aplikasi.
