<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id', 'nim',  'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'alamat', 'no_telp', 'fakultas_id', 'program_studi_id',
         'tahun_masuk', 'nama_ortu', 'pekerjaan_ortu',
        'no_telp_ortu', 'status', 'tahun_lulus'
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
        // Asumsi foreign key di tabel Mahasiswa adalah 'program_studi_id'
        return $this->belongsTo(Program_studi::class, 'program_studi_id');
    }
    public function tracerStudy()
{
    return $this->hasOne(TracerStudy::class);
}

/**
 * Cek apakah alumni sudah mengisi tracer study
 */
public function haFilledTracerStudy()
{
    return $this->tracerStudy()->exists();
}

/**
 * Scope untuk alumni yang sudah lulus
 */
public function scopeAlumni($query)
{
    return $query->where('status', 'lulus');
}

/**
 * Scope untuk alumni yang belum mengisi tracer study
 */
public function scopeBelumIsiTracerStudy($query)
{
    return $query->whereDoesntHave('tracerStudy');
}
 public function getStatusTextAttribute()
    {
        $statuses = [
            'aktif' => 'Aktif',
            'lulus' => 'Lulus',
            'pindah' => 'Pindah',
            'keluar' => 'Keluar',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) return null;
        return $this->tanggal_lahir->age;
    }

    /**
     * Check apakah sudah mengisi tracer study
     */
    public function hasFilledTracerStudy()
    {
        return $this->tracerStudy()->exists();
    }

    /**
     * Check apakah sudah lulus dan eligible untuk tracer study
     */
    public function isEligibleForTracerStudy()
    {
        return $this->status === 'lulus' && $this->tahun_lulus !== null;
    }
}