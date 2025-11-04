<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KerjasamaIndustri extends Model
{
    protected $table = 'kerjasama_industri';

    protected $fillable = [
        'perusahaan_id', 'jenis_kerjasama', 'judul', 'deskripsi',
        'tanggal_mulai', 'tanggal_berakhir', 'dokumen_mou',
        'status', 'pic_sekolah', 'pic_industri', 'nilai_kontrak', 'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'nilai_kontrak' => 'decimal:2',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }
}