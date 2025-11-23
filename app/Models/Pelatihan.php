<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $table = 'pelatihan';

    protected $fillable = [
        'judul', 'deskripsi', 'jenis', 'instruktur', 'tempat',
        'tanggal_mulai', 'tanggal_selesai', 'kuota', 'jumlah_peserta',
        'biaya', 'materi', 'sertifikat_template', 'status', 'thumbnail'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'biaya' => 'decimal:2',
        'kuota' => 'integer',
        'jumlah_peserta' => 'integer',
    ];

    public function peserta()
    {
        return $this->belongsToMany(Siswa::class, 'peserta_pelatihan')
                    ->withPivot('status_pendaftaran', 'status_kehadiran', 'nilai', 'sertifikat', 'feedback', 'tanggal_daftar')
                    ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
}