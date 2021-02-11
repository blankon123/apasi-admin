<?php

namespace App\Listeners;

use App\Events\PublikasiSPRPCommited;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use Illuminate\Support\Facades\Notification;

class PublikasiSPRPCommitedListener
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
     * @param  PublikasiSPRPCommited  $event
     * @return void
     */
    public function handle(PublikasiSPRPCommited $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = " Lengkap SPRP";

        Notification::send($admin, new PublikasiNotif($event->user, $event->publikasi, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new PublikasiNotif($event->user, $event->publikasi, $msg));
        }

        $pubHis = PublikasiHistori::create([
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

        // $maildata = [
        //     'konten' =>
        //     'Publikasi \
        //     **' . $event->publikasi->judul_publikasi . '**\
        //     Oleh \
        //     **' . $event->user->name . '**\
        //     Pada \
        //     **' . Carbon::now()->isoFormat('dddd, D MMMM Y HH:MM') . '** ',
        //     'judul' => 'Notifikasi Pengisian Detail Perwajahan',
        //     'subcopy' =>
        //     'Dalam 3 Hari Kedepan, Publikasi Akan Secara Otomatis di-Proses. Jika Menemukan Kesalahan Pada Publikasi ,Harap Segera Melakukan Perbaikan pada Aplikasi. \
        //     - \
        //     Terima Kasih 😊',
        // ];
        // Mail::to($user->email)->locale('id')->queue(new KabidMail($maildata));
    }
}
