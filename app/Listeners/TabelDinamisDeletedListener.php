<?php

namespace App\Listeners;

use App\Events\TabelDinamisDeleted;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisDeletedListener
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
     * @param  TabelDinamisDeleted  $event
     * @return void
     */
    public function handle(TabelDinamisDeleted $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Tabel ter-Hapus";
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
            'user_id' => $event->user->id,
        ]);
        Notification::send($admin, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
    }
}