<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KerjasamaIndustri extends Model
{
    protected $table = 'kerjasama_industri';

    protected $fillable = [
        'perusahaan_id', 'jenis_kerjasama', 'jenis_dokumen', 'lingkup_kerjasama', 'judul', 'deskripsi',
        'tanggal_mulai', 'tanggal_berakhir',
        'dokumen_mou', 'dokumen_moa', 'dokumen_kontrak', 'dokumen_surat_kerjasama',
        'status', 'pic_sekolah', 'pic_industri',
        'jabatan_pic_industri', 'no_telp_pic_industri', 'email_pic_industri',
        'nilai_kontrak',
        'catatan', 'alasan_penolakan', 'pengirim',
        'alasan_penolakan_perusahaan',
        'mou_disetujui_at', 'moa_kontrak_diunggah_at', 'disetujui_perusahaan_at', 'disetujui_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'nilai_kontrak' => 'decimal:2',
        'mou_disetujui_at' => 'datetime',
        'moa_kontrak_diunggah_at' => 'datetime',
        'disetujui_perusahaan_at' => 'datetime',
        'disetujui_at' => 'datetime',
    ];

    /**
     * Apakah kerjasama ini dikirim oleh admin ke perusahaan?
     */
    public function dariAdmin(): bool
    {
        return $this->pengirim === 'admin';
    }

    /**
     * Apakah kerjasama ini menunggu persetujuan perusahaan (dikirim oleh admin)?
     */
    public function menungguACC(): bool
    {
        return $this->pengirim === 'admin' && $this->status === 'menunggu_persetujuan_perusahaan';
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    /**
     * Label tahapan alur kerja sama (untuk ditampilkan di UI).
     *
     * Alur saat ini disederhanakan menjadi 2 langkah:
     *  1. Perusahaan mengajukan & mengunggah dokumen (MoU/MoA/Surat Kerjasama) -> status: proposal
     *  2. Admin langsung meng-ACC (menyetujui) atau menolak pengajuan          -> status: aktif / batal
     *
     * Status lain (mou_disetujui, menunggu_persetujuan_perusahaan, dsb.) tetap
     * dipertahankan agar data lama (alur sebelumnya) masih dapat ditampilkan.
     */
    public function tahapanLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'proposal' => 'Menunggu Persetujuan (ACC) Admin',
            'negosiasi' => 'Dalam Negosiasi',
            'mou_disetujui' => 'MoU Disetujui — Menunggu Admin Menyiapkan MoA & Kontrak',
            'menunggu_persetujuan_perusahaan' => $this->pengirim === 'admin'
                ? 'Menunggu Persetujuan (ACC) Perusahaan'
                : 'Menunggu Persetujuan Perusahaan atas MoA & Kontrak',
            'aktif' => 'Aktif (Kerja Sama Berjalan)',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan/Ditolak',
            'nonaktif' => 'Nonaktif',
            default => ucfirst($this->status),
        };
    }

    /**
     * Label siapa yang mengirimkan kerjasama ini.
     */
    public function pengirimLabel(): string
    {
        return match ($this->pengirim) {
            'admin' => 'Dikirim oleh Admin Sekolah',
            'perusahaan' => 'Diajukan oleh Perusahaan',
            default => '-',
        };
    }

    /**
     * Label jenis dokumen yang dipilih & diunggah perusahaan.
     */
    public function jenisDokumenLabel(): string
    {
        return match ($this->jenis_dokumen) {
            'mou' => 'MoU (Memorandum of Understanding)',
            'moa' => 'MoA (Memorandum of Agreement)',
            'surat_kerjasama' => 'Surat Kerjasama',
            default => '-',
        };
    }

    /**
     * Path dokumen yang sesuai dengan jenis_dokumen yang dipilih perusahaan.
     */
    public function dokumenUtama(): ?string
    {
        return match ($this->jenis_dokumen) {
            'mou' => $this->dokumen_mou,
            'moa' => $this->dokumen_moa,
            'surat_kerjasama' => $this->dokumen_surat_kerjasama,
            default => null,
        };
    }

    /**
     * Label lingkup/cakupan kerja sama.
     */
    public function lingkupLabel(): string
    {
        return match ($this->lingkup_kerjasama) {
            'dalam_negeri' => 'Dalam Negeri',
            'luar_negeri' => 'Luar Negeri',
            'swasta' => 'Swasta',
            'lainnya' => 'Lainnya',
            default => '-',
        };
    }

    /**
     * Daftar pilihan lingkup kerja sama (untuk dropdown di form).
     */
    public static function lingkupOptions(): array
    {
        return [
            'dalam_negeri' => 'Dalam Negeri',
            'luar_negeri' => 'Luar Negeri',
            'swasta' => 'Swasta',
            'lainnya' => 'Lainnya',
        ];
    }

    /**
     * Daftar pilihan jenis dokumen (untuk dropdown di form).
     */
    public static function jenisDokumenOptions(): array
    {
        return [
            'mou' => 'MoU (Memorandum of Understanding)',
            'moa' => 'MoA (Memorandum of Agreement)',
            'surat_kerjasama' => 'Surat Kerjasama',
        ];
    }
}