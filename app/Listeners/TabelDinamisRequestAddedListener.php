<?php

namespace App\Listeners;

use App\Events\TabelDinamisRequestAdded;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisRequestAddedListener
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
     * @param  TabelDinamisRequestAdded  $event
     * @return void
     */
    public function handle(TabelDinamisRequestAdded $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->tabel->user->id);
        $msg = "Tambah Tabel";
        $keterangan = "Tambah Tabel";
        Notification::send($admin, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        }

        $tabelHis = TabelDinamisHistori::create([
            'tabel_dinamis_id' => $event->tabel->id,
            'keterangan' => $msg,
            'data' => ['excel' => $event->fileName],
            'user_id' => $event->user->id,
        ]);

        $tabelHis->pekerjaan()->create([
            'nama' => 'Tambah Tabel ' . $event->tabel->judul_tabel,
            'status' => 0,
            'keterangan' => $keterangan,
            'tipe_pekerjaan' => 'tambah tabel',
            'color' => 'indigo darken-1',
        ]);
        Notification::send($user, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
    }
}