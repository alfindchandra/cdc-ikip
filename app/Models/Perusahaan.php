<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $fillable = [
        'user_id', 'nama_perusahaan', 'bidang_usaha', 'alamat',
        'kota', 'provinsi', 'kode_pos', 'no_telp', 'email',
        'website', 'nama_pic', 'jabatan_pic', 'no_telp_pic',
        'email_pic', 'status_kerjasama', 'tanggal_kerjasama',
        'logo', 'deskripsi'
    ];

    protected $casts = [
        'tanggal_kerjasama' => 'date',
    ];

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