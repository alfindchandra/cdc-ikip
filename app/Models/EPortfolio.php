<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EPortfolio extends Model
{
    protected $table = 'e_portfolios';

    protected $fillable = [
        'mahasiswa_id',
        'profil_kompetensi',
        'pengalaman_kerja',
        'prestasi',
        'sertifikat',
        'profil_path',
        'pengalaman_path',
        'prestasi_path',
        'sertifikat_path',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
