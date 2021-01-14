<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publikasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Get all of the files for the publikasis.
     */
    public function files()
    {
        return $this->hasMany(PublikasiFile::class);
    }
}