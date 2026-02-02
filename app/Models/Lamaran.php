<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    protected $table = 'lamaran';

    protected $fillable = [
        'lowongan_id', 'mahasiswa_id', 'cv', 'surat_lamaran',
        'portofolio', 'status', 'tanggal_melamar', 'catatan'
    ];

    protected $casts = [
        'tanggal_melamar' => 'datetime',
    ];

    public function lowongan()
    {
        return $this->belongsTo(LowonganKerja::class, 'lowongan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
