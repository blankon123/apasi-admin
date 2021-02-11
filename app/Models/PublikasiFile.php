<?php

namespace App\Models;

use App\Models\Publikasi;
use App\Models\PublikasiHistori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublikasiFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['publikasi_id', 'file', 'publikasi_histori_id', 'icon', 'keterangan'];

    /**
     * Get publikasi.
     */
    public function publikasi()
    {
        return $this->belongsTo(Publikasi::class);
    }

    /**
     * Get publikasiHistoris.
     */
    public function publikasiHistori()
    {
        return $this->belongsTo(PublikasiHistori::class);
    }
}