<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id', 'nim',  'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'alamat', 'no_telp', 'fakultas', 'program_studi',
         'tahun_masuk', 'nama_ortu', 'pekerjaan_ortu',
        'no_telp_ortu', 'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pkl()
    {
        return $this->hasMany(Pkl::class);
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class);
    }

    public function pelatihan()
    {
        return $this->belongsToMany(Pelatihan::class, 'peserta_pelatihan')
                    ->withPivot('status_pendaftaran', 'status_kehadiran', 'nilai', 'sertifikat')
                    ->withTimestamps();
    }


    // Relasi ke Fakultas
    public function fakultas()
    {
        // Pastikan nama tabel dan kunci asing benar
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    // Relasi ke Program Studi
    public function programStudi()
    {
        // Asumsi foreign key di tabel Siswa adalah 'program_studi_id'
        return $this->belongsTo(Program_studi::class, 'program_studi_id');
    }
}