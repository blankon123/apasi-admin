<?php

namespace App\Models;

use App\Models\Pekerjaan;
use App\Models\TabelDinamis;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelDinamisHistori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_dinamis_historis';

    /**
     * Cast Data from Json to Array.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tabel_id',
        'keterangan',
        'perubahan',
        'data',
        'user_id',
    ];

    /**
     * Get tabel.
     */
    public function tabelDinamis()
    {
        return $this->belongsTo(TabelDinamis::class);
    }

    /**
     * Get bidang.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'color', 'nama_bidang', 'name']);
    }

    /**
     * Get all of the tabel dinamis's pekerjaans.
     */
    public function pekerjaan()
    {
        return $this->morphOne(Pekerjaan::class, 'pekerjaanable');
    }

}