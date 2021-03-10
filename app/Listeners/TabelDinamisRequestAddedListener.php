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
        $msg = "Request Tambah Tabel";
        Notification::send($admin, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        }

        $tabelHis = TabelDinamisHistori::create([
            'tabel_id' => $event->tabel->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

        Notification::send($user, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
    }
}