<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    protected $table = 'tracer_study';

    protected $fillable = [
        'siswa_id',
        'status_pekerjaan',
        
        // Data Pekerjaan
        'nama_perusahaan',
        'posisi',
        'bidang_pekerjaan',
        'alamat_perusahaan',
        'jenis_perusahaan',
        'penghasilan',
        'relevansi_pekerjaan',
        'cara_mendapat_pekerjaan',
        'waktu_tunggu_kerja',
        
        // Data Melanjutkan Studi
        'nama_institusi',
        'jenjang_studi',
        'jurusan_studi',
        'sumber_biaya',
        
        // Data Wirausaha
        'nama_usaha',
        'bidang_usaha',
        'jumlah_karyawan',
        'omzet_usaha',
        
        // Kepuasan & Feedback
        'kepuasan_pendidikan',
        'saran_kurikulum',
        'saran_fasilitas',
        'saran_umum',
        'kompetensi_yang_digunakan',
        
        // Kontak
        'email_saat_ini',
        'no_telp_saat_ini',
        'linkedin',
        
        'tanggal_isi',
    ];

    protected $casts = [
        'kompetensi_yang_digunakan' => 'array',
        'tanggal_isi' => 'datetime',
        'waktu_tunggu_kerja' => 'integer',
        'jumlah_karyawan' => 'integer',
        'kepuasan_pendidikan' => 'integer',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Accessors
    public function getStatusPekerjaanLabelAttribute()
    {
        $labels = [
            'bekerja' => 'Bekerja',
            'wirausaha' => 'Wirausaha',
            'melanjutkan_studi' => 'Melanjutkan Studi',
            'belum_bekerja' => 'Belum Bekerja',
        ];

        return $labels[$this->status_pekerjaan] ?? '-';
    }

    public function getJenisPerusahaanLabelAttribute()
    {
        $labels = [
            'pemerintah' => 'Pemerintah',
            'swasta' => 'Swasta',
            'bumn' => 'BUMN',
            'startup' => 'Startup',
            'lainnya' => 'Lainnya',
        ];

        return $labels[$this->jenis_perusahaan] ?? '-';
    }

    public function getRelevansiPekerjaanLabelAttribute()
    {
        $labels = [
            'sangat_relevan' => 'Sangat Relevan',
            'relevan' => 'Relevan',
            'cukup_relevan' => 'Cukup Relevan',
            'tidak_relevan' => 'Tidak Relevan',
        ];

        return $labels[$this->relevansi_pekerjaan] ?? '-';
    }

    public function getJenjangStudiLabelAttribute()
    {
        $labels = [
            'd3' => 'D3',
            's1' => 'S1',
            's2' => 'S2',
            's3' => 'S3',
            'kursus' => 'Kursus/Sertifikasi',
            'pelatihan' => 'Pelatihan',
        ];

        return $labels[$this->jenjang_studi] ?? '-';
    }

    // Scopes
    public function scopeBekerja($query)
    {
        return $query->where('status_pekerjaan', 'bekerja');
    }

    public function scopeWirausaha($query)
    {
        return $query->where('status_pekerjaan', 'wirausaha');
    }

    public function scopeMelanjutkanStudi($query)
    {
        return $query->where('status_pekerjaan', 'melanjutkan_studi');
    }

    public function scopeBelumBekerja($query)
    {
        return $query->where('status_pekerjaan', 'belum_bekerja');
    }
}