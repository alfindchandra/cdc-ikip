<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $fillable = [
       'user_id',
        'bidang_usaha',
        'jenis_pt',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telp',
        'no_hp',
        'website',
        'nama_pimpinan',
        'tahun_berdiri',
        'jumlah_karyawan',
        'visi',
        'misi',
        'cv_perusahaan',
        'deskripsi',
        'status_kerjasama',
    ];

    protected $casts = [
        'tanggal_kerjasama' => 'date',
        'tahun_berdiri' => 'integer',
        'jumlah_karyawan' => 'integer',
    ];

    /**
     * Daftar pilihan Jenis PT (untuk dropdown di form Registrasi PT).
     */
    public static function jenisPtOptions(): array
    {
        return [
            'PT Perorangan' => 'PT Perorangan',
            'PT Persekutuan Modal' => 'PT Persekutuan Modal (Umum)',
            'CV (Commanditaire Vennootschap)' => 'CV (Commanditaire Vennootschap)',
            'Firma' => 'Firma',
            'Koperasi' => 'Koperasi',
            'Yayasan' => 'Yayasan',
            'BUMN/BUMD' => 'BUMN/BUMD',
            'Lainnya' => 'Lainnya',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pkl()
    {
        return $this->hasMany(Pkl::class);
    }

    public function lowonganKerja()
    {
        return $this->hasMany(LowonganKerja::class);
    }

    public function kerjasamaIndustri()
    {
        return $this->hasMany(KerjasamaIndustri::class);
    }
}