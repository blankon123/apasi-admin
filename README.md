<h1 align="center"><b>📚APASI</b></h1>
<p align="center">Aplikasi Pembantu Diseminasi</p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang APASI

APASI adalah singkatan dari Aplikasi Pembantu Diseminasi yang dapat digunakan untuk manajemen publikasi dan tabel seksi Diseminasi dan Layanan Statistik seluruh BPS se-Indonesia. Beberapa produk yang dapat diatur yakni

- Alur Pembuatan Publikasi
- Alur Rilis Publikasi
- Alur Tabel Dinamis
- Otomatis Sinkronisasi Tabel Dinamis
- Manajemen Petugas Diseminasi
- Notifikasi Email & Bot Telegram
- Alur Pekerjaan (Belum, Sedang, dan Sudah Dikerjakan) Petugas

## Penggunaan

APASI dikembangkan menggunakan [Laravel 8](https://laravel.com/docs/contributions). Untuk pemasangan, sangat disarankan menggunakan server Linux dengan memanfaatkan Laravel Sail. Instalasi Laravel Sail dapat diikuti di [website](https://laravel.com/docs/8.x/sail).

Setelah melakukan instalasi Laravel Sail,selanjutnya yaitu melakukan cloning git ke lokal dengan perintah `git clone <this-repo-url>` , lalu membuat file `.env` dengan perintah `cp .env.example .env` , lalu mengisi informasi pada file `.env`.

Selanjutnya yaitu menjalankan Laravel Sail dengan perintah berikut:

```sh
#Menjalankan Laravel Sail secara Daemon
./vendor/bin/sail up -d

#Instalasi NPM dan Compile front-end
./vendor/bin/sail npm install
./vendor/bin/sail npm run production

#Instalasi PHP package
./vendor/bin/sail composer install

#Migrasi ke database MySql di Sail
./vendor/bin/sail artisan migrate

#Memasukkan default user ke database
./vendor/bin/sail artisan db:seed

#Menjalankan queue scheduler
./vendor/bin/sail artisan queue:work
```

## Kontribusi

Silahkan menggunakan dan merubah aplikasi ini sesuai dengan kebutuhan masing-masing, jika terdapat celah kemanan, temuan dapat langsung dimasukkan ke _issue_ pada git repo ini.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
