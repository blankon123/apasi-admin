<?php

namespace App\Listeners;

use App\Events\TabelDinamisDataEdited;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisDataEditedListener
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
     * @param  TabelDinamisDataEdited  $event
     * @return void
     */
    public function handle(TabelDinamisDataEdited $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Perubahan Data";
        $keterangan = "Perubahan Data";

        if ($event->tabel->user) {
            $user = User::find($event->tabel->user->id);
            if ($user->role != "ADMIN") {
                Notification::send($user, new TabelDinamisNotif($event->user, $event->tabel, $msg));
            }
        }
        Notification::send($admin, new TabelDinamisNotif($event->user, $event->tabel, $msg));

        $tabelHis = TabelDinamisHistori::create([
            'tabel_dinamis_id' => $event->tabel->id,
            'keterangan' => $msg,
            'perubahan' => $event->perubahan,
            'data' => $event->tabel->data,
            'user_id' => $event->user->id,
        ]);

        $tabelHis->pekerjaan()->create([
            'nama' => 'Data Tabel ' . $event->tabel->judul_tabel,
            'status' => 0,
            'keterangan' => $keterangan,
            'tipe_pekerjaan' => 'data tabel',
            'color' => 'purple darken-1',
        ]);
        Notification::send($admin, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
    }
}