<?php

namespace App\Listeners;

use App\Events\PublikasiImported;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use Illuminate\Support\Facades\Notification;

class PublikasiImportedListener
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
     * @param  PublikasiImported  $event
     * @return void
     */
    public function handle(PublikasiImported $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = "Telah di-Import";
        Notification::send($admin, new PublikasiNotif($event->user, $event->publikasi, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new PublikasiNotif($event->user, $event->publikasi, $msg));
        }

        $pubHis = PublikasiHistori::create([
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

    }
}