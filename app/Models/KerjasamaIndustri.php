<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KerjasamaIndustri extends Model
{
    protected $table = 'kerjasama_industri';

    protected $fillable = [
        'perusahaan_id', 'jenis_kerjasama', 'judul', 'deskripsi',
        'tanggal_mulai', 'tanggal_berakhir',
        'dokumen_mou', 'dokumen_moa', 'dokumen_kontrak',
        'status', 'pic_sekolah', 'pic_industri', 'nilai_kontrak',
        'catatan', 'alasan_penolakan',
        'mou_disetujui_at', 'moa_kontrak_diunggah_at', 'disetujui_perusahaan_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'nilai_kontrak' => 'decimal:2',
        'mou_disetujui_at' => 'datetime',
        'moa_kontrak_diunggah_at' => 'datetime',
        'disetujui_perusahaan_at' => 'datetime',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    /**
     * Label tahapan alur kerja sama (untuk ditampilkan di UI).
     */
    public function tahapanLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'proposal' => 'Menunggu Review MoU oleh Admin',
            'negosiasi' => 'Dalam Negosiasi',
            'mou_disetujui' => 'MoU Disetujui — Menunggu Admin Menyiapkan MoA & Kontrak',
            'menunggu_persetujuan_perusahaan' => 'Menunggu Persetujuan Perusahaan atas MoA & Kontrak',
            'aktif' => 'Aktif (Kerja Sama Berjalan)',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan/Ditolak',
            'nonaktif' => 'Nonaktif',
            default => ucfirst($this->status),
        };
    }
}