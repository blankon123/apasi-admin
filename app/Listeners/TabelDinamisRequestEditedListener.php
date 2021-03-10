<?php

namespace App\Listeners;

use App\Events\TabelDinamisRequestEdited;
use App\Models\TabelDinamisHistori;
use App\Models\User;
use App\Notifications\TabelDinamisNotif;
use App\Notifications\TelegramTabelDinamisNotification;
use Illuminate\Support\Facades\Notification;

class TabelDinamisRequestEditedListener
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
     * @param  TabelDinamisRequestEdited  $event
     * @return void
     */
    public function handle(TabelDinamisRequestEdited $event)
    {
        $admin = User::where('role', "=", "admin")->first();
        $user = User::find($event->tabel->user->id);
        $msg = "Request Edit Tabel";
        $keterangan = "Perubahan";
        $jumlahPerubahan = 0;

        Notification::send($admin, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        if ($user->role != "ADMIN") {
            Notification::send($user, new TabelDinamisNotif($event->user, $event->tabel, $msg));
        }

        if ($event->tabel_old->judul_tabel != $event->tabel->judul_tabel) {
            $keterangan = $keterangan . " Judul";
            $jumlahPerubahan++;
        }
        if ($event->tabel_old->category_id != $event->tabel->category_id) {
            if ($jumlahPerubahan) {
                $keterangan = $keterangan . ",";
            }
            $keterangan = $keterangan . " Kategori";
            $jumlahPerubahan++;
        }
        if ($event->tabel_old->subject_id != $event->tabel->subject_id) {
            if ($jumlahPerubahan) {
                $keterangan = $keterangan . ",";
            }
            $keterangan = $keterangan . " Subjek";
            $jumlahPerubahan++;
        }

        if ($jumlahPerubahan) {
            $event->tabel->is_revisi = 1;
            $event->tabel->save();

            $tabelHis = TabelDinamisHistori::create([
                'tabel_id' => $event->tabel->id,
                'keterangan' => $msg,
                'user_id' => $event->user->id,
            ]);

            $tabelHis->pekerjaan()->create([
                'nama' => 'Edit Detail Tabel ' . $event->tabel->judul_tabel,
                'status' => 0,
                'keterangan' => $keterangan,
                'tipe_pekerjaan' => 'edit tabel',
                'color' => 'teal darken-1',
            ]);
            Notification::send($user, new TelegramTabelDinamisNotification($event->user, $event->tabel, $msg, ""));
        }

    }
}