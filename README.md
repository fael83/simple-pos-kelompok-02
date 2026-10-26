<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Skenario Uji Manual (Manual Testing Scenarios)

Berikut adalah daftar pengujian manual untuk memastikan setiap peran pengguna berfungsi dengan baik di dalam aplikasi Simple POS:

### 1. Peran: Admin
* **Tujuan**: Memastikan admin dapat mengakses halaman manajemen pengguna dan mengelola data akun dengan benar.
* **Langkah-langkah**:
  1. Buka halaman login di browser (`http://127.0.0.1:8000/login`)[cite: 2].
  2. Masukkan email `admin@pos.test` dan kata sandi `password`, lalu klik **Masuk**.
  3. Setelah diarahkan ke dashboard, navigasikan ke menu **Manajemen Pengguna** (Users).
  4. Coba tambahkan atau ubah status salah satu akun pengguna.
* **Hasil yang Diharapkan**: Admin berhasil masuk ke dashboard khusus admin, dapat melihat daftar seluruh pengguna, serta sukses melakukan penambahan atau pembaruan data pengguna.

### 2. Peran: Manager
* **Tujuan**: Memastikan manager dapat mengakses laporan penjualan dan memantau ringkasan transaksi bisnis.
* **Langkah-langkah**:
  1. Buka halaman login di browser (`http://127.0.0.1:8000/login`)[cite: 2].
  2. Masukkan email `manager@pos.test` dan kata sandi `password`, lalu klik **Masuk**.
  3. Dari dashboard utama, pilih menu **Laporan** (Reports) atau **Analisis Penjualan**.
  4. Periksa data grafik atau tabel rekapitulasi transaksi.
* **Hasil yang Diharapkan**: Manager berhasil masuk, data laporan penjualan/statistik tampil dengan akurat sesuai dengan data di dalam database.

### 3. Peran: Kasir
* **Tujuan**: Memastikan kasir dapat melakukan simulasi transaksi penjualan baru (POS) dengan memilih produk dan memproses pembayaran.
* **Langkah-langkah**:
  1. Buka halaman login di browser (`http://127.0.0.1:8000/login`)[cite: 2].
  2. Masukkan email `kasir@pos.test` dan kata sandi `password`, lalu klik **Masuk**.
  3. Buka halaman **Transaksi / Kasir** (POS).
  4. Pilih beberapa produk[cite: 4], masukkan jumlah pembayaran, lalu klik **Bayar**[cite: 4] untuk menyelesaikan transaksi[cite: 3].
* **Hasil yang Diharapkan**: Kasir berhasil masuk ke antarmuka POS, produk dapat dimasukkan ke keranjang, transaksi berhasil diproses dengan munculnya pesan "Transaksi berhasil disimpan"[cite: 3], dan stok produk berkurang secara otomatis.

## Implementasi Queued Job pada Simple POS

### Proses yang Dipindahkan ke Queue: Pengiriman Email Struk Pembayaran (`SendTransactionReceiptJob`)

#### Alasan Pemindahan ke Queue:
1. **Peningkatan Performa & Response Time:** 
   Pengiriman email melalui protokol SMTP memerlukan koneksi 
   ke server eksternal yang memakan waktu (network latency). 
   Memindahkan proses ini ke background Queue memastikan antarmuka kasir/POS merespons 
   secara instan tanpa perlu menunggu SMTP server selesai mengirim email.
   
2. **Pengalaman Pengguna (User Experience):** 
   Kasir dapat langsung mencetak struk fisik atau melanjutkan ke 
   transaksi berikutnya tanpa tertahan oleh proses *loading* pengiriman email.

3. **Keandalan & Retry Mechanism:** 
   Jika terjadi kegagalan jaringan saat mengirim email, 
   Laravel Queue menyediakan fitur *automatic retry* (`tries`), 
   sehingga email tetap dapat terkirim saat jaringan kembali stabil 
   tanpa menggagalkan transaksi yang sudah berhasil disimpan ke database.