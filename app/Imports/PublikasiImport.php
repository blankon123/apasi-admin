<?php

namespace App\Imports;

use App\Events\PublikasiImported;
use App\Models\Enums;
use App\Models\Publikasi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class PublikasiImport implements OnEachRow, WithHeadingRow
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

        $publikasi = new Publikasi;
        $notify_user_id = null;

        $publikasi->judul_publikasi = $row['judul_publikasi'];
        $publikasi->jenis_arc = $row['tipe_arc'] == "ARC" ? 1 : 2;
        $publikasi->arc = $row['arc'] ? date('Y-m-d', strtotime($row['arc'])) : null;
        $publikasi->batas_upload = $row['upload'] ? date('Y-m-d', strtotime($row['upload'])) : null;
        $publikasi->tahun_rilis = $row['arc'] ? date('Y', strtotime($row['arc'])) : null;
        if ($this->importedBy->role == 'ADMIN') {
            $publikasi->user_id = Enums::KODE_BIDANG[strtoupper(preg_replace('/\s+/', '', $row['bidang']))]['kode'];
            $notify_user_id = $publikasi->user_id;
        } elseif ($this->importedBy->role == 'SM' && $row['judul_publikasi'] != null) {
            $publikasi->user_id = $this->importedBy->id;
            $notify_user_id = 1;
        } else {
            die;
        }
        $publikasi->save();
        event(new PublikasiImported($publikasi, $this->importedBy));
    }
}