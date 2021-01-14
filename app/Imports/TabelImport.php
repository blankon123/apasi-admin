<?php

namespace App\Imports;

use App\Tabel;
use App\User;
use App\Notifikasi;
use App\Perbaikan;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TabelImport implements OnEachRow, WithHeadingRow
{
    /**
    * @param string User who import this
    *
    * @return User Id
        */
    public function __construct(User $importedBy)
    {
        $this->importedBy = $importedBy;
    }
        
    /**
    * @param Row $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $tabel = new Tabel;
        $notif = new Notifikasi;
        $notif_user_id = null;

        $tabel->nama_tabel = $row['judul_tabel'];
        $tabel->tipe_arc = $row['rilis'];
        $tabel->arc = $row['arc'] ? date('Y-m-d', strtotime($row['arc'])) : null;
        if ($this->importedBy->role=='admin') {
            $tabel->user_id = config('kode.KODE_BIDANG'.$row['bidang']);
            $notif_user_id = $tabel->user_id;
        } elseif ($this->importedBy->role=='sm' && $row['judul_tabel']!=null) {
            $tabel->user_id = $this->importedBy->id;
            $notif_user_id = 1;
        } else {
            die;
        }
        $tabel->save();

        $notif->tipe = 'tabel';
        $notif->id_target = $tabel->id;
        $notif->user_id = $notif_user_id;
        $notif->stage_id = config('kode.TABEL_BARU');
        $notif->save();
                
        $perbaikan = new Perbaikan;
        $perbaikan->tabel_id = $tabel->id;
        $perbaikan->user_id = $tabel->user_id;
        $perbaikan->stage_id = config('kode.TABEL_BARU');
        $perbaikan->keterangan = "Tabel Pertama Kali Dibuat";
        $perbaikan->save();
    }
}