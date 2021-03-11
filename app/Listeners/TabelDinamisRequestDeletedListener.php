<?php

namespace App\Listeners;

use App\Events\TabelDinamisRequestDeleted;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisRequestDeletedListener
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
     * @param  TabelDinamisRequestDeleted  $event
     * @return void
     */
    public function handle(TabelDinamisRequestDeleted $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Request Hapus";
        if ($event->tabel->user) {
            $user = User::find($event->tabel->user->id);
            if ($user->role != "ADMIN") {
                Notification::send($user, new TabelDinamisNotif($event->user, $event->tabel, $msg));
            }
        }
        Notification::send($admin, new TabelDinamisNotif($event->user, $event->tabel, $msg));

        $event->tabel->is_deleted = 1;
        $event->tabel->save();

        $tabelHis = TabelDinamisHistori::create([
            'tabel_dinamis_id' => $event->tabel->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

        $tabelHis->pekerjaan()->create([
            'nama' => 'Hapus Tabel ' . $event->tabel->judul_tabel . ' di Web',
            'status' => 0,
            'tipe_pekerjaan' => 'hapus tabel',
            'color' => 'red darken-1',
        ]);
        Notification::send($admin, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
    }
}