<?php

namespace App\Listeners;

use App\Events\PublikasiDesainRevised;
use App\Models\PublikasiFile;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use App\Notifications\TelegramPublikasiNotification;
use Illuminate\Support\Facades\Notification;

class PublikasiDesainRevisedListener
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
     * @param  PublikasiDesainRevised  $event
     * @return void
     */
    public function handle(PublikasiDesainRevised $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = "Revisi Desain Cover";

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
            'file' => '/publikasi_desain/' . $event->fileName['desain'],
            'publikasi_histori_id' => $pubHis->id,
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => "Desain",
            "icon" => "mdi-format-paint",
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

        $link_desain = urlencode(env('APP_URL', "http://localhost:8000") . '/publikasi_desain/' . $event->fileName['desain']);
        Notification::send($user, new TelegramPublikasiNotification($event->user, $event->publikasi, $msg, $link_desain));

    }
}
