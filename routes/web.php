<?php

use App\Mail\KabidMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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
Route::get('/tesMail', function () {
    $maildata = [
        'konten' =>
        'Publikasi \
        **' . "A" . '**\
        Oleh \
        **' . env("APP_URL", "cangcimengs") . '**\
        Pada \
        **' . Carbon::now()->isoFormat('dddd, D MMMM Y HH:MM') . '** \


[**📓Link Draft**](' . "C" . ') - [**🖌Link Cover**](' . ')

        ',
        'judul' => 'Notifikasi Unggah Draft',
        'subcopy' =>
        'Dalam 3 Hari Kedepan, Publikasi Akan Secara Otomatis di-Proses. Jika Menemukan Kesalahan Pada Publikasi ,Harap Segera Melakukan Perbaikan pada Aplikasi. \
        - \
        Terima Kasih 😊',
    ];

    Mail::to("thosan.suganda@bps.go.id")->locale('id')->send(new KabidMail($maildata));
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');