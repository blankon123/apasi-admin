<?php

namespace App\Listeners;

use App\Events\PublikasiUploaded;
use App\Mail\KabidMail;
use App\Models\PublikasiFile;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use App\Notifications\TelegramPublikasiNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PublikasiUploadedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PublikasiUploaded  $event
     * @return void
     */
    public function handle(PublikasiUploaded $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = "Draft, Desain, dan Surat Rilis Upload Website";

        Notification::send($admin, new PublikasiNotif($event->user, $event->publikasi, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new PublikasiNotif($event->user, $event->publikasi, $msg));
        }

        $pubHis = PublikasiHistori::create([
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

        $pubFile = PublikasiFile::create([
            'file' => '/publikasi_draft/' . $event->fileName['draft'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Draft",
            'icon' => "mdi-book-alphabet",
        ]);

        $pubFile = PublikasiFile::create([
            'file' => '/publikasi_desain/' . $event->fileName['desain'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Desain",
            "icon" => "mdi-format-paint",
        ]);

        $pubFile = PublikasiFile::create([
            'file' => '/publikasi_rilis/' . $event->fileName['rilis'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Rilis",
            "icon" => "mdi-book-check",
        ]);

        $link_draft = urlencode(env('APP_URL', "http://localhost:8000") . '/publikasi_draft/' . $event->fileName['draft']);
        $link_desain = urlencode(env('APP_URL', "http://localhost:8000") . '/publikasi_desain/' . $event->fileName['desain']);

        $maildata = [
            'konten' =>
            'Publikasi \
            **' . $event->publikasi->judul_publikasi . '**\
            Oleh \
            **' . $event->user->name . '**\
            Pada \
            **' . Carbon::now()->isoFormat('dddd, D MMMM Y HH:MM') . '** \


[**📓Link Draft**](' . $link_draft . ') - [**🖌Link Cover**](' . $link_desain . ')

            ',
            'judul' => 'Notifikasi Draft Akhir',
            'subcopy' =>
            'Dalam 3 Hari Kedepan, Publikasi Akan Secara Otomatis Tampil di Website. Jika Menemukan Kesalahan Pada Publikasi ,Harap Segera Melakukan Perbaikan pada Aplikasi. \
            - \
            Terima Kasih 😊',
        ];
        Notification::send($user, new TelegramPublikasiNotification($event->user, $event->publikasi, $msg, ""));
        Mail::to($user->email)->locale('id')->queue(new KabidMail($maildata));
    }
}