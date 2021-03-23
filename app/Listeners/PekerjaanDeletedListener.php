<?php

namespace App\Listeners;

use App\Events\PekerjaanDeleted;
use App\Models\Publikasi;
use App\Models\TabelDinamis;
use App\Models\User;
use App\Notifications\PekerjaanNotif;
use Illuminate\Support\Facades\Notification;

class PekerjaanDeletedListener
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
     * @param  PekerjaanDeleted  $event
     * @return void
     */
    public function handle(PekerjaanDeleted $event)
    {
        $pekerjaan = $event->pekerjaan;
        if ($pekerjaan->pekerjaanable_type == "App\Models\PublikasiHistori"
            && $pekerjaan->pekerjaanable->publikasi->is_revisi == 1
            && $pekerjaan->tipe_pekerjaan == "revisi") {
            $publikasi = Publikasi::find($pekerjaan->pekerjaanable->publikasi->id)->update(['is_revisi' => 0]);
        };
        if ($pekerjaan->pekerjaanable_type == "App\Models\TabelDinamisHistori"
            && $pekerjaan->pekerjaanable->tabelDinamis->is_revisi == 1
            && $pekerjaan->tipe_pekerjaan == "edit tabel") {
            $tabelDInamis = TabelDinamis::find($pekerjaan->pekerjaanable->tabelDinamis->id)->update(['is_revisi' => 0]);
        };
        if ($pekerjaan->pekerjaanable_type == "App\Models\TabelDinamisHistori"
            && $pekerjaan->pekerjaanable->tabelDinamis->is_deleted == 1
            && $pekerjaan->tipe_pekerjaan == "hapus tabel") {
            $tabelDInamis = TabelDinamis::find($pekerjaan->pekerjaanable->tabelDinamis->id)->update(['is_deleted' => 0]);
        };
        $admin = User::where('role', "=", "admin")->first();
        $msg = "Pekerjaan Telah di-Hapus";
        Notification::send($admin, new PekerjaanNotif($event->user, $event->pekerjaan, $msg));
    }
}
