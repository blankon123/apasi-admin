<?php

namespace App\Models;

use App\Models\TabelDinamisHistori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelDinamis extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tabel_dinamises';

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
        'judul_tabel',
        'tabel_web_id',
        'subject_id',
        'category_id',
        'stage_id',
        'user_id',
        'data',
        'is_revisi',
        'is_deleted',
        'unit',
        'note',
    ];

    /**
     * Get all of the historis for the TabelHistori.
     */
    public function historis()
    {
        return $this->hasMany(TabelDinamisHistori::class)->latest();
    }

    /**
     * Get bidang.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select(['id', 'color', 'nama_bidang', 'name']);
    }

}