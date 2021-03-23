<?php

namespace App\Listeners;

use App\Events\TabelDinamisEditedDone;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisEditedDoneListener
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
     * @param  TabelDinamisEditedDone  $event
     * @return void
     */
    public function handle(TabelDinamisEditedDone $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Perubahan Detail Done";
        $keterangan = "Perubahan Detail Done";

        $event->tabel->is_revisi = 0;
        $event->tabel->save();

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