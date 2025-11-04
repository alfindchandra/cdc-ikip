<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowonganKerja extends Model
{
    protected $table = 'lowongan_kerja';

    protected $fillable = [
        'perusahaan_id', 'judul', 'posisi', 'deskripsi',
        'kualifikasi', 'benefit', 'tipe_pekerjaan', 'lokasi',
        'gaji_min', 'gaji_max', 'kuota', 'tanggal_mulai',
        'tanggal_berakhir', 'status', 'jumlah_pelamar', 'thumbnail'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'gaji_min' => 'decimal:2',
        'gaji_max' => 'decimal:2',
        'kuota' => 'integer',
        'jumlah_pelamar' => 'integer',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
                     ->where('tanggal_berakhir', '>=', now());
    }
}
