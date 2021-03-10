<?php

namespace App\Models;

class Enums
{
    /*
    |--------------------------------------------------------------------------
    | Kode Publikasi, Stage, tabel, dan Bidang
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk menyeragamkan dan menyamarkan kode tahapan tiap publikasi.
    | Disamarkan agar mudah disimpan di database dan dibaca di kode.
     */
    public const STAGE_PUBLIKASI = [
        'PUBLIKASI_BARU' => [
            'kode' => 11,
            'display' => 'Publikasi Baru Ditambahkan',
            'color' => 'dark',
        ],
        'PUBLIKASI_DRAFT' => [
            'kode' => 12,
            'display' => 'Draft Publikasi Sudah Terunggah',
            'color' => 'dark',
        ],
        'PUBLIKASI_LAYOUT' => [
            'kode' => 13,
            'display' => 'Publikasi Sudah di-Layout',
            'color' => 'info',
        ],
        'PUBLIKASI_RILIS' => [
            'kode' => 14,
            'display' => 'Publikasi Baru Ditambahkan',
            'color' => 'success',
        ],
        'PUBLIKASI_REVISI' => [
            'kode' => 15,
            'display' => 'Publikasi Baru Ditambahkan',
            'color' => 'warning',
        ],
    ];
    public const KODE_PUBLIKASI = [
        'PUBLIKASI_ORIENTASI' => ['portrait' => 1, 'landscape' => 2],
        'PUBLIKASI_COVER_OLEH' => ['ipds' => 1, 'sm' => 2],
        'PUBLIKASI_DITERBITKAN_UNTUK' => ['eksternal' => 1, 'internal' => 2],
        'PUBLIKASI_JENIS_ARC' => ['arc' => 1, 'nonarc' => 2],
        'PUBLIKASI_BAHASA' => ['indo' => 1, 'eng' => 2, 'indo_eng' => 3],
    ];
    public const STAGE_TABEL = [
        'TABEL_BARU' => 31,
        'TABEL_TAMBAH' => 32,
        'TABEL_KURANG' => 33,
        'TABEL_TAMPIL_WEB' => 34,
        'TABEL_EDIT_DESKRIPSI' => 35,
        'TABEL_LAIN' => 36,
    ];
    public const KODE_BIDANG = [
        'IPDS' => [
            'kode' => 1,
            'singkat' => 'IPDS',
            'panjang' => 'Bidang Integrasi Pengolahan dan Diseminasi Statistik',
        ],
        'TU' => [
            'kode' => 2,
            'singkat' => 'TU',
            'panjang' => 'Bagian Tata Usaha',
        ],
        'SOSIAL' => [
            'kode' => 3,
            'singkat' => 'Sosial',
            'panjang' => 'Bidang Statistik Sosial',
        ],
        'DISTRIBUSI' => [
            'kode' => 4,
            'singkat' => 'Distribusi',
            'panjang' => 'Bidang Statistik Distribusi',
        ],
        'PRODUKSI' => [
            'kode' => 5,
            'singkat' => 'Produksi',
            'panjang' => 'Bidang Statistik Produksi',
        ],
        'NERACA' => [
            'kode' => 6,
            'singkat' => 'Nerwilis',
            'panjang' => 'Bidang Neraca Wilayah dan Analisis Statistik',
        ],
    ];
}