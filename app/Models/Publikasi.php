<?php

namespace App\Models;

use App\Models\Petugas;
use App\Models\PublikasiFile;
use App\Models\PublikasiHistori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publikasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul_publikasi',
        'arc',
        'tahun_rilis',
        'jenis_arc',
        'user_id',
        'is_revisi',
    ];

    /**
     * Get all of the files for the publikasis.
     */
    public function files()
    {
        return $this->hasMany(PublikasiFile::class);
    }

    /**
     * Get all of the files for the publikasis.
     */
    public function historis()
    {
        return $this->hasMany(PublikasiHistori::class)->latest();
    }

    /**
     * Get bidang.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get bidang.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(Petugas::class, 'uploaded_by', 'id');
    }
}