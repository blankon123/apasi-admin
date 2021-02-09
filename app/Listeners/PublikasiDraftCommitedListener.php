<?php

namespace App\Listeners;

use App\Events\PublikasiDraftCommited;
use App\Mail\KabidMail;
use App\Models\PublikasiHistori;
use App\Models\User;
use App\Notifications\PublikasiNotif;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PublikasiDraftCommitedListener
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
     * @param  PublikasiDraftCommited  $event
     * @return void
     */
    public function handle(PublikasiDraftCommited $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->publikasi->user->id);
        $msg = "Draft";

        Notification::send($admin, new PublikasiNotif($event->user, $event->publikasi, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new PublikasiNotif($event->user, $event->publikasi, $msg));
        }

        $pubHis = PublikasiHistori::create([
            'publikasi_id' => $event->publikasi->id,
            'keterangan' => $msg,
            'user_id' => $event->user->id,
        ]);

        $maildata = [
            'judul' => 'Draft Publikasi ' . $event->publikasi->judul . ' Telah Diunggah Oleh ' . $event->user->name . ' Pada: ' . Carbon::now()->format('l jS \\of F Y h:i:s A'),
            'konten' => 'https://www.positronx.io',
        ];
        Mail::to($request->user())->send(new KabidMail($maildata));
    }
}