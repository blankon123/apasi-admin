<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pekerjaan extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nama',
        'status',
        'tipe_pekerjaan',
        'color',
        'keterangan',
        'petugas_id',
    ];

    /**
     * Get the parent object model (post or video).
     */
    public function pekerjaanable()
    {
        return $this->morphTo();
    }
}