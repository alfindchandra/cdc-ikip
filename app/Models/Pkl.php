<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pkl extends Model
{
    protected $table = 'pkl';

    protected $fillable = [
        'siswa_id', 'perusahaan_id', 'pembimbing_sekolah',
        'pembimbing_industri', 'tanggal_mulai', 'tanggal_selesai',
        'posisi', 'divisi', 'status', 'nilai_akhir',
        'sertifikat', 'laporan_pkl', 'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'nilai_akhir' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function jurnalPkl()
    {
        return $this->hasMany(JurnalPkl::class);
    }
}
