<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    protected $table = 'lamaran';

    protected $fillable = [
        'lowongan_id', 'mahasiswa_id',
        'cv', 'surat_lamaran', 'Ijazah', 'ktp', 'foto', 'sertifikat',
        'status', 'tanggal_melamar', 'catatan'
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

    /**
     * Daftar field dokumen lamaran beserta label & keterangan wajib/opsional.
     * Dipakai bersama di berbagai view (mahasiswa, admin, perusahaan)
     * supaya konsisten dengan skema tabel `lamaran`.
     */
    public static function documentFields(): array
    {
        return [
            'cv'             => ['label' => 'Curriculum Vitae (CV)', 'required' => true],
            'surat_lamaran'  => ['label' => 'Surat Lamaran', 'required' => true],
            'Ijazah'         => ['label' => 'Ijazah', 'required' => true],
            'ktp'            => ['label' => 'KTP', 'required' => true],
            'foto'           => ['label' => 'Pas Foto', 'required' => true],
            'sertifikat'     => ['label' => 'Sertifikat Pendukung', 'required' => false],
        ];
    }
}
