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

Fitur reset password, verifikasi email, two-factor authentication, dan passkey belum diaktifkan. Password disimpan sebagai hash, dan login dibatasi lima percobaan per menit untuk kombinasi email dan alamat IP.

Tampilan menggunakan Bootstrap 5.3.8 dari CDN dengan tema biru TuanKost. Koneksi internet diperlukan untuk memuat Bootstrap. Gunakan `APP_LOCALE=id` untuk pesan autentikasi dan validasi berbahasa Indonesia.

## Foto kamar

Aktifkan ekstensi GD di PHP, lalu jalankan `composer install`, `php artisan migrate`, dan `php artisan storage:link`. Setelah login, buka **Edit Kamar** untuk mengunggah atau menghapus foto. Thumbnail juga ditampilkan di halaman detail; klik thumbnail untuk membuka versi lebih besar.

Unggahan mendukung JPEG, PNG, GIF, dan WebP, maksimal lima gambar per permintaan, 2 MB dan 4000 × 4000 piksel per gambar. Intervention Image menyimpan versi JPEG dengan lebar maksimal 1000 piksel dan thumbnail maksimal 300 piksel pada disk `public`. Foto dapat diakses melalui URL publiknya, sedangkan operasi unggah dan hapus memerlukan login.

## Middleware admin (Pertemuan 11)

Akun terdaftar mendapat level `user`. Kedua level dapat mengakses dashboard dan pengelolaan kamar. Menu **Pengguna** dan semua route CRUD pengguna hanya dapat diakses oleh akun berlevel `admin`.

Untuk menyiapkan admin pertama, jalankan `php artisan migrate`, daftarkan akun milik sendiri, lalu jalankan `php artisan tinker`. Ganti email contoh dengan alamat akun yang telah terdaftar:

```php
$user = App\Models\User::where('email', 'email-anda@example.com')->firstOrFail();
$user->level = App\Models\User::LEVEL_ADMIN;
$user->save();
```

Muat ulang halaman setelah mengubah level. Melalui menu **Pengguna**, admin dapat menambah, melihat daftar, mengubah, dan menghapus akun. Penghapusan akun sendiri dan perubahan level akun sendiri dibatasi.

## Email login dan registrasi (Pertemuan 12)

Mailtrap Email Sandbox menampung email pengujian dari TuanKost. Buka Mailtrap, pilih Email Sandbox dan inbox pengujian, lalu salin SMTP username dan password dari tab integrasi ke file lokal `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username_smtp_dari_mailtrap
MAIL_PASSWORD=password_smtp_dari_mailtrap
MAIL_FROM_ADDRESS="no-reply@tuankost.test"
MAIL_FROM_NAME="TuanKost"
QUEUE_CONNECTION=database
```

Isi kredensial sendiri di `.env`. Setelah mengubah konfigurasi, jalankan `php artisan config:clear` dan `php artisan queue:restart`. Jalankan `php artisan migrate` untuk memastikan tabel antrean tersedia.

Jalankan `php artisan serve` dan `php artisan queue:work --tries=3` pada dua terminal berbeda. Pastikan MySQL tetap berjalan. Email selamat datang dikirim saat registrasi berhasil. Login otomatis setelah registrasi tidak mengirim email login tambahan. Login berhasil berikutnya mengirim pemberitahuan waktu login. Logout, login gagal, serta pembuatan akun melalui CRUD admin tidak mengirim email.

Listener `SendAccountActivityEmail` ditemukan otomatis oleh Laravel untuk event `Login` dan `Registered`. Mailable `AccountActivityMail` diproses melalui database queue setelah transaksi commit, dengan maksimum tiga percobaan dan jeda retry 30 detik. Template berada di `resources/views/emails/account-activity.blade.php`.

Untuk menguji, registrasikan akun contoh lalu cek inbox Sandbox. Logout dan login kembali untuk memeriksa email login. Hasil pengiriman masuk ke inbox Mailtrap Sandbox. Jika worker gagal, periksa `php artisan queue:failed`; setelah konfigurasi diperbaiki, ulangi job yang sesuai dengan `php artisan queue:retry ID_JOB`.

## Pengujian otomatis

Jalankan `php artisan test`. Konfigurasi pengujian memakai SQLite dalam memori dan tidak mengubah data MySQL aplikasi.
