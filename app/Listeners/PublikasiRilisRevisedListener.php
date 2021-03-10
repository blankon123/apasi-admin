<?php

namespace App\Listeners;

use App\Events\PublikasiRilisRevised;
use App\Models\PublikasiFile;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use App\Notifications\TelegramPublikasiNotification;
use Illuminate\Support\Facades\Notification;

class PublikasiRilisRevisedListener
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
     * @param  PublikasiRilisRevised  $event
     * @return void
     */
    public function handle(PublikasiRilisRevised $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = " Revisi Surat Rilis";

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
            'file' => '/publikasi_rilis/' . $event->fileName['rilis'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Rilis",
            "icon" => "mdi-book-check",
        ]);

        if ($event->publikasi->is_revisi == 0) {
            $pubHis->pekerjaan()->create([
                'nama' => 'Revisi Publikasi ' . $event->publikasi->judul_publikasi,
                'status' => 0,
                'tipe_pekerjaan' => 'revisi',
                'color' => 'orange darken-1',
            ]);
            $event->publikasi->is_revisi = 1;
            $event->publikasi->save();
        }

        Notification::send($user, new TelegramPublikasiNotification($event->user, $event->publikasi, $msg, ""));

    }
}
