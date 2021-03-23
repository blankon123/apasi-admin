<?php

namespace App\Listeners;

use App\Events\TabelDinamisAddedDone;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisAddedDoneListener
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
     * @param  TabelDinamisAddedDone  $event
     * @return void
     */
    public function handle(TabelDinamisAddedDone $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Penambahan Tabel Done";
        $keterangan = "Penambahan Tabel Done";

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
            'perubahan' => "Data Ter-Upload",
            'data' => $event->tabel->data,
            'user_id' => $event->user->id,
        ]);

        Notification::send($admin, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
        $event->tabel->delete();
    }
}