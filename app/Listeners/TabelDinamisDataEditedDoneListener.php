<?php

namespace App\Listeners;

use App\Events\TabelDinamisDataEditedDone;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisDataEditedDoneListener
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
     * @param  TabelDinamisDataEditedDone  $event
     * @return void
     */
    public function handle(TabelDinamisDataEditedDone $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Perubahan Data Done";
        $keterangan = "Perubahan Data Done";

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
    }
}