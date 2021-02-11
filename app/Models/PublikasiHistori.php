<?php

namespace App\Models;

use App\Models\Publikasi;
use App\Models\PublikasiFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublikasiHistori extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['publikasi_id', 'keterangan', 'user_id'];

    /**
     * Get publikasi.
     */
    public function publikasi()
    {
        return $this->belongsTo(Publikasi::class);
    }

    /**
     * Get files.
     */
    public function file()
    {
        return $this->hasMany(PublikasiFile::class);
    }

    /**
     * Get bidang.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}