<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/mailable', function () {
//     $maildata = [
//         'konten' =>
//         'Publikasi \
//         **' . 'Cangcimengs' . '**\
//         Oleh \
//         **' . 'Cangcimengs' . '**\
//         Pada \
//         **' . Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:MM') . '**

// [**📓Link Draft**](' . env('APP_URL', "http://localhost:8000") . '/publikasi_draft/' . 'cangs' . ') - [**🖌Link Cover**](' . env('APP_URL', "http://localhost:8000") . '/publikasi_desain/' . 'cings' . ')

//         ',
//         'judul' => 'Notifikasi Unggah Draft',
//         'subcopy' =>
//         'Dalam 3 Hari Kedepan, Publikasi Akan Secara Otomatis di-Proses. Jika Menemukan Kesalahan Pada Publikasi ,Harap Segera Melakukan Perbaikan pada Aplikasi. \
//         - \
//         Terima Kasih 😊',
//     ];
//     https://api.telegram.org/bot1620043866:AAHMQWaGxmU6oT7dASSg-F1RGLo50crFkUM/sendMessage?chat_id=@apasikalteng&text=cangcimeng
//     return new App\Mail\KabidMail($maildata);
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');