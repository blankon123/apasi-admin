<?php

namespace App\Listeners;

use App\Events\PublikasiErataRevised;
use App\Models\PublikasiFile;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use App\Notifications\TelegramNotification;
use Illuminate\Support\Facades\Notification;

class PublikasiErataRevisedListener
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
     * @param  PublikasiErataRevised  $event
     * @return void
     */
    public function handle(PublikasiErataRevised $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = "Dokumen Erata";

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
            'file' => '/publikasi_erata/' . $event->fileName['erata'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Erata",
            'icon' => "mdi-book-cog",
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

        $link_erata = urlencode(env('APP_URL', "http://localhost:8000") . '/publikasi_erata/' . $event->fileName['erata']);
        Notification::send($user, new TelegramNotification($event->user, $event->publikasi, $msg, $link_erata));
    }
}